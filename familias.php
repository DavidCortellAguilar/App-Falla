<?php
require_once __DIR__ . '/config.php';
require_admin();
ensure_audit_columns($pdo);
$page_title = 'Familias';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();

    if (($_POST['action'] ?? '') === 'delete') {
        $idDelete = (int) $_POST['id'];
        $stmt = $pdo->prepare("SELECT nombre FROM familias WHERE id=:id");
        $stmt->execute(['id' => $idDelete]);
        $familiaDelete = $stmt->fetch();
        $familiaNombreDelete = $familiaDelete['nombre'] ?? ('ID ' . $idDelete);

        $pdo->prepare("DELETE FROM familias WHERE id=:id")->execute(['id' => $idDelete]);
        log_activity($pdo, 'Eliminado', 'familias', 'Familia eliminada: ' . $familiaNombreDelete, $idDelete, $familiaNombreDelete);
        redirect('familias.php');
    }

    $id = (int) ($_POST['id'] ?? 0);
    $esNuevaFamilia = $id === 0;
    $representante1 = !empty($_POST['representante_1_id']) ? (int) $_POST['representante_1_id'] : null;
    $representante2 = !empty($_POST['representante_2_id']) ? (int) $_POST['representante_2_id'] : null;

    // Seguridad: los representantes solo pueden ser falleros que pertenezcan a esta familia.
    // En familias nuevas no se asignan representantes hasta que haya miembros dentro de la familia.
    $representantesValidos = [];
    if ($id) {
        $stmtValidos = $pdo->prepare("SELECT id FROM falleros WHERE familia_id=:familia_id");
        $stmtValidos->execute(['familia_id' => $id]);
        $representantesValidos = array_map('intval', $stmtValidos->fetchAll(PDO::FETCH_COLUMN));
    }

    if ($representante1 && !in_array($representante1, $representantesValidos, true)) {
        $representante1 = null;
    }
    if ($representante2 && !in_array($representante2, $representantesValidos, true)) {
        $representante2 = null;
    }
    if ($representante1 && $representante2 && $representante1 === $representante2) {
        $representante2 = null;
    }

    $data = [
        'nombre' => trim($_POST['nombre'] ?? ''),
        'representante_fallero_id' => $representante1,
        'observaciones' => trim($_POST['observaciones'] ?? ''),
    ];

    if ($id) {
        $data['id'] = $id;
        $data['updated_by'] = current_user_id();
        $pdo->prepare("UPDATE familias SET nombre=:nombre, representante_fallero_id=:representante_fallero_id, observaciones=:observaciones, updated_at=NOW(), updated_by=:updated_by WHERE id=:id")->execute($data);
    } else {
        $data['created_by'] = current_user_id();
        $pdo->prepare("INSERT INTO familias (nombre, representante_fallero_id, observaciones, created_by) VALUES (:nombre, :representante_fallero_id, :observaciones, :created_by)")->execute($data);
    }

    $familiaIdGuardada = $id ?: (int) $pdo->lastInsertId();
    $representantes = array_values(array_filter(array_unique([$representante1, $representante2])));
    $pdo->prepare("DELETE FROM familia_representantes WHERE familia_id=:familia_id")->execute(['familia_id' => $familiaIdGuardada]);
    foreach ($representantes as $repId) {
        if ($repId > 0) {
            $pdo->prepare("INSERT IGNORE INTO familia_representantes (familia_id, fallero_id) VALUES (:familia_id, :fallero_id)")
                ->execute(['familia_id' => $familiaIdGuardada, 'fallero_id' => $repId]);
        }
    }

    log_activity(
        $pdo,
        $esNuevaFamilia ? 'Creado' : 'Modificado',
        'familias',
        ($esNuevaFamilia ? 'Familia creada: ' : 'Familia modificada: ') . $data['nombre'],
        $familiaIdGuardada,
        $data['nombre']
    );
    redirect('familias.php');
}

$edit = null;
$editRepresentantes = [];
$editMiembros = [];
if (!empty($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM familias WHERE id=:id");
    $stmt->execute(['id' => (int) $_GET['edit']]);
    $edit = $stmt->fetch();
    if ($edit) {
        $stmt = $pdo->prepare("SELECT fallero_id FROM familia_representantes WHERE familia_id=:id ORDER BY created_at ASC, fallero_id ASC LIMIT 2");
        $stmt->execute(['id' => (int) $edit['id']]);
        $editRepresentantes = array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));

        $stmt = $pdo->prepare("SELECT id, nombre, apellidos FROM falleros WHERE familia_id=:familia_id ORDER BY apellidos, nombre");
        $stmt->execute(['familia_id' => (int) $edit['id']]);
        $editMiembros = $stmt->fetchAll();
    }
}

$familias = $pdo->query("
    SELECT fa.*, CONCAT(f.apellidos, ', ', f.nombre) AS representante_principal,
    COALESCE(NULLIF(TRIM(CONCAT_WS(' ', cu_f.nombre, cu_f.apellidos)), ''), cu.dni, 'Sistema') AS creado_por_nombre,
    COALESCE(NULLIF(TRIM(CONCAT_WS(' ', uu_f.nombre, uu_f.apellidos)), ''), uu.dni, 'Sin editar') AS editado_por_nombre,
    (SELECT GROUP_CONCAT(CONCAT(fr_f.apellidos, ', ', fr_f.nombre) ORDER BY fr.created_at ASC, fr.fallero_id ASC SEPARATOR '<br>') FROM familia_representantes fr INNER JOIN falleros fr_f ON fr_f.id=fr.fallero_id WHERE fr.familia_id=fa.id) AS representantes,
    (SELECT COUNT(*) FROM falleros ff WHERE ff.familia_id = fa.id) AS miembros
    FROM familias fa
    LEFT JOIN falleros f ON f.id = fa.representante_fallero_id
    LEFT JOIN users cu ON cu.id = fa.created_by
    LEFT JOIN falleros cu_f ON cu_f.id = cu.fallero_id
    LEFT JOIN users uu ON uu.id = fa.updated_by
    LEFT JOIN falleros uu_f ON uu_f.id = uu.fallero_id
    ORDER BY fa.nombre
")->fetchAll();

include __DIR__ . '/header.php';
include __DIR__ . '/sidebar.php';
?>

<main class="main-dashboard">
    <header class="dashboard-topbar">
        <button class="mobile-menu-btn" type="button" onclick="toggleSidebar()">☰</button>
        <div>
            <h1>Familias</h1>
            <p>Gestión de familias y responsables</p>
        </div>
        <div class="topbar-actions">
            <a href="index.php" class="topbar-btn">← Panel</a>
            <a href="logout.php" class="topbar-btn">➤ Salir</a>
        </div>
    </header>

    <section class="dashboard-content">

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card-modern">
            <h2 class="h5 mb-3"><?= $edit ? 'Editar familia' : 'Nueva familia' ?></h2>
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="id" value="<?= e($edit['id'] ?? '') ?>">

                <div class="mb-3">
                    <label class="form-label">Nombre familiar</label>
                    <input class="form-control" name="nombre" value="<?= e($edit['nombre'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Representante 1</label>
                    <select class="form-select" name="representante_1_id" <?= $edit ? '' : 'disabled' ?>>
                        <option value="">Sin representante</option>
                        <?php foreach ($editMiembros as $f): ?>
                            <option value="<?= (int)$f['id'] ?>" <?= ((int)($editRepresentantes[0] ?? 0) === (int)$f['id']) ? 'selected' : '' ?>>
                                <?= e($f['apellidos'] . ', ' . $f['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!$edit): ?>
                        <small class="text-muted">Primero guarda la familia y asigna miembros desde el censo. Después podrás elegir representantes.</small>
                    <?php else: ?>
                        <small class="text-muted">Solo aparecen falleros/as que pertenecen a esta familia.</small>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Representante 2</label>
                    <select class="form-select" name="representante_2_id" <?= $edit ? '' : 'disabled' ?>>
                        <option value="">Sin segundo representante</option>
                        <?php foreach ($editMiembros as $f): ?>
                            <option value="<?= (int)$f['id'] ?>" <?= ((int)($editRepresentantes[1] ?? 0) === (int)$f['id']) ? 'selected' : '' ?>>
                                <?= e($f['apellidos'] . ', ' . $f['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Observaciones</label>
                    <textarea class="form-control" name="observaciones"><?= e($edit['observaciones'] ?? '') ?></textarea>
                </div>

                <button class="btn btn-primary w-100">Guardar</button>
            </form>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card-modern table-card">
            <table class="table align-middle">
                <thead><tr><th>Familia</th><th>Representantes</th><th>Miembros</th><th>Auditoría</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($familias as $familia): ?>
                    <tr>
                        <td><a href="familia_detalle.php?id=<?= (int) $familia['id'] ?>" class="fw-bold text-decoration-none"><?= e($familia['nombre']) ?></a></td>
                        <td><?= $familia['representantes'] ?: e($familia['representante_principal']) ?></td>
                        <td><?= e((string)$familia['miembros']) ?></td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-light" href="familia_detalle.php?id=<?= $familia['id'] ?>">Ver</a>
                                <a class="btn btn-sm btn-light" href="familias.php?edit=<?= $familia['id'] ?>">Editar</a>
                            <form method="post" class="d-inline" onsubmit="return confirm('¿Eliminar familia?')">
                                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $familia['id'] ?>">
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

</section>
</main>

<?php include __DIR__ . '/footer.php'; ?>
