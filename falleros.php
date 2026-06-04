<?php
require_once __DIR__ . '/config.php';
require_admin();
ensure_audit_columns($pdo);
$page_title = 'Falleros';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();

    if (($_POST['action'] ?? '') === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM falleros WHERE id = :id");
        $stmt->execute(['id' => (int) $_POST['id']]);
        log_activity($pdo, 'delete', 'falleros', 'Fallero eliminado ID ' . (int) $_POST['id']);
        redirect('falleros.php');
    }

    $id = (int) ($_POST['id'] ?? 0);
    $dniNuevo = strtoupper(trim($_POST['dni'] ?? ''));

    $data = [
        'nombre' => trim($_POST['nombre'] ?? ''),
        'apellidos' => trim($_POST['apellidos'] ?? ''),
        'dni' => $dniNuevo,
        'fecha_nacimiento' => $_POST['fecha_nacimiento'] ?: null,
        'telefono' => trim($_POST['telefono'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'direccion' => trim($_POST['direccion'] ?? ''),
        'tipo' => $_POST['tipo'] ?? 'adulto',
        'sexo' => $_POST['sexo'] ?? 'Hombre',
        'estado' => $_POST['estado'] ?? 'activo',
        'familia_id' => $_POST['familia_id'] ? (int) $_POST['familia_id'] : null,
    ];

    try {
        $pdo->beginTransaction();

        if ($id > 0) {
            $stmt = $pdo->prepare("SELECT dni FROM falleros WHERE id = :id FOR UPDATE");
            $stmt->execute(['id' => $id]);
            $falleroActual = $stmt->fetch();

            if (!$falleroActual) {
                throw new RuntimeException('No se ha encontrado el fallero que quieres editar.');
            }

            $dniAnterior = strtoupper(trim((string) $falleroActual['dni']));

            $stmt = $pdo->prepare("
                SELECT id, fallero_id
                FROM users
                WHERE dni = :dni
                  AND (fallero_id IS NULL OR fallero_id <> :fallero_id)
                LIMIT 1
            ");
            $stmt->execute([
                'dni' => $dniNuevo,
                'fallero_id' => $id,
            ]);

            if ($stmt->fetch()) {
                throw new RuntimeException('No se puede guardar: ya existe otro usuario con ese DNI.');
            }

            $data['id'] = $id;
            $data['updated_by'] = current_user_id();

            $stmt = $pdo->prepare("
                UPDATE falleros
                SET nombre=:nombre,
                    apellidos=:apellidos,
                    dni=:dni,
                    fecha_nacimiento=:fecha_nacimiento,
                    telefono=:telefono,
                    email=:email,
                    direccion=:direccion,
                    tipo=:tipo,
                    sexo=:sexo,
                    estado=:estado,
                    familia_id=:familia_id,
                    updated_at=NOW(),
                    updated_by=:updated_by
                WHERE id=:id
            ");
            $stmt->execute($data);

            $stmt = $pdo->prepare("
                UPDATE users
                SET dni = :dni_nuevo,
                    fallero_id = :fallero_id,
                    updated_at = NOW()
                WHERE fallero_id = :fallero_id
                   OR dni = :dni_anterior
            ");
            $stmt->execute([
                'dni_nuevo' => $dniNuevo,
                'fallero_id' => $id,
                'dni_anterior' => $dniAnterior,
            ]);

            log_activity($pdo, 'update', 'falleros', 'Fallero actualizado');
        } else {
            $stmt = $pdo->prepare("SELECT id FROM falleros WHERE dni = :dni LIMIT 1");
            $stmt->execute(['dni' => $dniNuevo]);

            if ($stmt->fetch()) {
                throw new RuntimeException('No se puede crear: ya existe un fallero con ese DNI.');
            }

            $stmt = $pdo->prepare("
                INSERT INTO falleros (nombre, apellidos, dni, fecha_nacimiento, telefono, email, direccion, tipo, sexo, estado, familia_id, created_by)
                VALUES (:nombre, :apellidos, :dni, :fecha_nacimiento, :telefono, :email, :direccion, :tipo, :sexo, :estado, :familia_id, :created_by)
            ");
            $data['created_by'] = current_user_id();
            $stmt->execute($data);

            log_activity($pdo, 'create', 'falleros', 'Fallero creado');
        }

        $pdo->commit();
        redirect('falleros.php');
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        $error = $e->getMessage();
    }
}

$edit = null;
if (!empty($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM falleros WHERE id = :id");
    $stmt->execute(['id' => (int) $_GET['edit']]);
    $edit = $stmt->fetch();
}

$q = trim($_GET['q'] ?? '');
$estadoFiltro = trim($_GET['estado'] ?? '');
$sql = "SELECT f.*, fa.nombre AS familia,
       COALESCE(NULLIF(TRIM(CONCAT_WS(' ', cu_f.nombre, cu_f.apellidos)), ''), cu.dni, 'Sistema') AS creado_por_nombre,
       COALESCE(NULLIF(TRIM(CONCAT_WS(' ', uu_f.nombre, uu_f.apellidos)), ''), uu.dni, 'Sin editar') AS editado_por_nombre
FROM falleros f
LEFT JOIN familias fa ON fa.id=f.familia_id
LEFT JOIN users cu ON cu.id = f.created_by
LEFT JOIN falleros cu_f ON cu_f.id = cu.fallero_id
LEFT JOIN users uu ON uu.id = f.updated_by
LEFT JOIN falleros uu_f ON uu_f.id = uu.fallero_id";
$where = [];
$params = [];

if ($q !== '') {
    $where[] = "(f.nombre LIKE :q OR f.apellidos LIKE :q OR f.dni LIKE :q)";
    $params['q'] = "%{$q}%";
}

if ($estadoFiltro !== '') {
    $where[] = "f.estado = :estado";
    $params['estado'] = $estadoFiltro;
}

if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " ORDER BY f.apellidos, f.nombre";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$falleros = $stmt->fetchAll();

$familias = $pdo->query("SELECT * FROM familias ORDER BY nombre")->fetchAll();

include __DIR__ . '/header.php';
include __DIR__ . '/sidebar.php';
?>

<main class="main-dashboard">
    <header class="dashboard-topbar">
        <button class="mobile-menu-btn" type="button" onclick="toggleSidebar()">☰</button>
        <div>
            <h1>Censo de faller@s</h1>
            <p>Gestión del censo de la falla</p>
        </div>
        <div class="topbar-actions">
            <a href="index.php" class="topbar-btn">← Panel</a>
            <a href="logout.php" class="topbar-btn">➤ Salir</a>
        </div>
    </header>

    <section class="dashboard-content">

<?php if ($error): ?>
    <div class="alert alert-danger mb-3">
        <?= e($error) ?>
    </div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card-modern">
            <h2 class="h5 mb-3"><?= $edit ? 'Editar fallero' : 'Nuevo fallero' ?></h2>
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="id" value="<?= e($edit['id'] ?? '') ?>">

                <div class="mb-2"><label class="form-label">Nombre</label><input class="form-control" name="nombre" value="<?= e($edit['nombre'] ?? '') ?>" required></div>
                <div class="mb-2"><label class="form-label">Apellidos</label><input class="form-control" name="apellidos" value="<?= e($edit['apellidos'] ?? '') ?>" required></div>
                <div class="mb-2"><label class="form-label">DNI</label><input class="form-control" name="dni" value="<?= e($edit['dni'] ?? '') ?>" required></div>
                <div class="mb-2"><label class="form-label">Fecha nacimiento</label><input class="form-control" type="date" name="fecha_nacimiento" value="<?= e($edit['fecha_nacimiento'] ?? '') ?>"></div>
                <div class="mb-2"><label class="form-label">Teléfono</label><input class="form-control" name="telefono" value="<?= e($edit['telefono'] ?? '') ?>"></div>
                <div class="mb-2"><label class="form-label">Email</label><input class="form-control" type="email" name="email" value="<?= e($edit['email'] ?? '') ?>"></div>

                <div class="mb-2">
                    <label class="form-label">Sexo</label>
                    <select class="form-select" name="sexo">
                        <option value="Hombre" <?= (($edit['sexo'] ?? 'Hombre') === 'Hombre') ? 'selected' : '' ?>>Hombre</option>
                        <option value="Mujer" <?= (($edit['sexo'] ?? '') === 'Mujer') ? 'selected' : '' ?>>Mujer</option>
                    </select>
                </div>

                <div class="mb-2"><label class="form-label">Dirección</label><input class="form-control" name="direccion" value="<?= e($edit['direccion'] ?? '') ?>"></div>

                <div class="row">
                    <div class="col-6 mb-2">
                        <label class="form-label">Tipo</label>
                        <select class="form-select" name="tipo">
                            <option value="adulto" <?= (($edit['tipo'] ?? '') === 'adulto') ? 'selected' : '' ?>>Adulto</option>
                            <option value="infantil" <?= (($edit['tipo'] ?? '') === 'infantil') ? 'selected' : '' ?>>Infantil</option>
                        </select>
                    </div>
                    <div class="col-6 mb-2">
                        <label class="form-label">Estado</label>
                        <select class="form-select" name="estado">
                            <?php foreach (['activo','inactivo','pendiente','baja'] as $estado): ?>
                                <option value="<?= $estado ?>" <?= (($edit['estado'] ?? '') === $estado) ? 'selected' : '' ?>><?= $estado ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Familia</label>
                    <select class="form-select" name="familia_id">
                        <option value="">Sin familia</option>
                        <?php foreach ($familias as $familia): ?>
                            <option value="<?= $familia['id'] ?>" <?= (($edit['familia_id'] ?? '') == $familia['id']) ? 'selected' : '' ?>><?= e($familia['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button class="btn btn-primary w-100"><?= $edit ? 'Guardar cambios' : 'Crear fallero' ?></button>
                <?php if ($edit): ?>
                    <a class="btn btn-light w-100 mt-2" href="falleros.php">Cancelar edición</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card-modern">
            <form class="row g-2 mb-3">
                <div class="col"><input class="form-control" name="q" placeholder="Buscar por nombre, apellidos o DNI" value="<?= e($q) ?>"></div>
                <div class="col-auto"><button class="btn btn-primary">Buscar</button></div>
                <div class="col-auto"><a class="btn btn-light" href="falleros.php">Limpiar</a></div>
            </form>

            <div class="table-card">
                <table class="table align-middle">
                    <thead><tr><th>Fallero</th><th>DNI</th><th>Tipo</th><th>Estado</th><th>Familia</th><th>Auditoría</th><th></th></tr></thead>
                    <tbody>
                    <?php foreach ($falleros as $fallero): ?>
                        <tr>
                            <td><?= e($fallero['apellidos'] . ', ' . $fallero['nombre']) ?></td>
                            <td><?= e($fallero['dni']) ?></td>
                            <td><?= e($fallero['tipo']) ?></td>
                            <td><?= e($fallero['estado']) ?></td>
                            <td><?= e($fallero['familia']) ?></td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-light" href="falleros.php?edit=<?= $fallero['id'] ?>">Editar</a>
                                <form method="post" class="d-inline" onsubmit="return confirm('¿Eliminar fallero?')">
                                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $fallero['id'] ?>">
                                    <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</section>
</main>

<?php include __DIR__ . '/footer.php'; ?>
