<?php
require_once __DIR__ . '/config.php';

if (is_logged()) {
    redirect('index.php');
}

$error = '';
$success = '';

$familias = $pdo->query("SELECT id, nombre FROM familias ORDER BY nombre")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    check_honeypot();
    check_turnstile();

    $nombre = trim($_POST['nombre'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $dni = strtoupper(trim($_POST['dni'] ?? ''));
    $telefono = trim($_POST['telefono'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $fechaNacimiento = $_POST['fecha_nacimiento'] ?: null;
    $tiposPermitidos = ['adulto', 'infantil', 'bebe'];
    $tipo = in_array($_POST['tipo'] ?? '', $tiposPermitidos, true) ? $_POST['tipo'] : 'adulto';
    $sexo = $_POST['sexo'] === 'Mujer' ? 'Mujer' : 'Hombre';
    $familiaModo = $_POST['familia_modo'] ?? 'sin_familia';
    $familiaId = null;
    $crearComoRepresentante = 0;

    if ($nombre === '' || $apellidos === '' || $dni === '') {
        $error = 'Completa nombre, apellidos y DNI.';
    } else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE dni = :dni");
        $stmt->execute(['dni' => $dni]);
        $existeUsuario = (int) $stmt->fetchColumn() > 0;

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM falleros WHERE dni = :dni");
        $stmt->execute(['dni' => $dni]);
        $existeFallero = (int) $stmt->fetchColumn() > 0;

        if ($existeUsuario || $existeFallero) {
            $error = 'Ya existe una solicitud o usuario con ese DNI.';
        }
    }

    if (!$error) {
        try {
            $pdo->beginTransaction();

            if ($familiaModo === 'existente' && !empty($_POST['familia_id'])) {
                $familiaId = (int) $_POST['familia_id'];
            } elseif ($familiaModo === 'nueva') {
                $nombreFamilia = trim($_POST['nombre_familia'] ?? '');
                if ($nombreFamilia === '') {
                    throw new RuntimeException('Debes indicar el nombre de la nueva familia.');
                }

                $pdo->prepare("INSERT INTO familias (nombre, observaciones) VALUES (:nombre, :observaciones)")
                    ->execute([
                        'nombre' => $nombreFamilia,
                        'observaciones' => 'Solicitud de alta pendiente de aprobación.',
                    ]);
                $familiaId = (int) $pdo->lastInsertId();
                $crearComoRepresentante = 1;
            }

            $stmt = $pdo->prepare("\n                INSERT INTO falleros (nombre, apellidos, dni, fecha_nacimiento, telefono, email, direccion, tipo, sexo, estado, familia_id)\n                VALUES (:nombre, :apellidos, :dni, :fecha_nacimiento, :telefono, :email, :direccion, :tipo, :sexo, 'pendiente', :familia_id)\n            ");
            $stmt->execute([
                'nombre' => $nombre,
                'apellidos' => $apellidos,
                'dni' => $dni,
                'fecha_nacimiento' => $fechaNacimiento,
                'telefono' => $telefono,
                'email' => $email,
                'direccion' => $direccion,
                'tipo' => $tipo,
                'sexo' => $sexo,
                'familia_id' => $familiaId,
            ]);
            $falleroId = (int) $pdo->lastInsertId();

            if ($crearComoRepresentante && $familiaId) {
                $pdo->prepare("UPDATE familias SET representante_fallero_id = :fallero_id WHERE id = :familia_id")
                    ->execute(['fallero_id' => $falleroId, 'familia_id' => $familiaId]);
            }

            $pdo->prepare("\n                INSERT INTO users (dni, password_hash, role, fallero_id, is_active)\n                VALUES (:dni, :password_hash, 'fallero', :fallero_id, 0)\n            ")->execute([
                'dni' => $dni,
                'password_hash' => '',
                'fallero_id' => $falleroId,
            ]);

            $pdo->commit();
            $success = 'Solicitud registrada correctamente. Un administrador debe aprobarla antes de poder acceder.';
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $error = $e->getMessage();
        }
    }
}

$page_title = 'Nuevo fallero';
include __DIR__ . '/header.php';
?>

<main class="login-content">
    <div class="card-modern login-card" style="max-width: 760px;">
        <div class="text-center mb-4">
            <div class="brand-icon mx-auto mb-3">
                <img src="./img/327113823_436363905290382_7275278403003823711_n.jpg" alt="Logo">
            </div>
            <h1 class="h3 mb-1">Solicitud de alta</h1>
            <p class="text-muted mb-0">Crea tu usuario y ficha de faller@. Quedará pendiente de aprobación.</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= e($success) ?></div>
            <a href="login.php" class="btn btn-primary w-100">Volver al login</a>
        <?php else: ?>
            <form method="post">
                <?= csrf_field() ?>
                <?= honeypot_field() ?>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input class="form-control" name="nombre" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellidos</label>
                        <input class="form-control" name="apellidos" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">DNI</label>
                        <input class="form-control" name="dni" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha de nacimiento</label>
                        <input class="form-control" type="date" name="fecha_nacimiento">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input class="form-control" name="telefono">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input class="form-control" type="email" name="email">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Dirección</label>
                        <input class="form-control" name="direccion">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tipo</label>
                        <select class="form-select" name="tipo">
                            <option value="adulto">Adulto</option>
                            <option value="infantil">Infantil</option>
                            <option value="bebe">Bebé</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Sexo</label>
                        <select class="form-select" name="sexo">
                            <option value="Hombre">Hombre</option>
                            <option value="Mujer">Mujer</option>
                        </select>
                    </div>
                </div>

                <hr class="my-4">

                <h2 class="h5">Familia</h2>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Opción</label>
                        <select class="form-select" name="familia_modo" id="familiaModo" onchange="toggleFamiliaFields()">
                            <option value="sin_familia">Sin familia por ahora</option>
                            <option value="existente">Asociarme a familia existente</option>
                            <option value="nueva">Crear nueva familia</option>
                        </select>
                    </div>
                    <div class="col-md-4 familia-existente d-none">
                        <label class="form-label">Familia existente</label>
                        <select class="form-select" name="familia_id">
                            <option value="">Selecciona familia</option>
                            <?php foreach ($familias as $familia): ?>
                                <option value="<?= (int) $familia['id'] ?>"><?= e($familia['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 familia-nueva d-none">
                        <label class="form-label">Nombre de la nueva familia</label>
                        <input class="form-control" name="nombre_familia" placeholder="Ej. Familia Martínez">
                    </div>
                </div>

                <hr class="my-4">
                <div class="alert border small" style="background:#fff3cd;color:#b45309;border-color:#fcd34d;">
                    En caso de no tener DNI (Bebé) se pondrá DNI de padre o madre, si es padre P12345678A, si es madre M12345678A (P o M + DNI del adulto).
                </div>
                <div class="alert alert-light border small" style="background:#fff3cd;color:#b45309;border-color:#fcd34d;">
                    Cuando envíes la inscripción no podrás modificar tus datos. Revisa bien la información antes de enviarla.
                </div>

                <div class="mt-4">
                    <?= turnstile_widget() ?>
                </div>

                <button class="btn btn-primary w-100 btn-lg mt-2">Enviar solicitud</button>
                <a href="login.php" class="btn btn-light w-100 mt-2">Volver al login</a>
            </form>
        <?php endif; ?>
    </div>
</main>

<script>
function toggleFamiliaFields() {
    const modo = document.getElementById('familiaModo').value;
    document.querySelectorAll('.familia-existente').forEach(el => el.classList.toggle('d-none', modo !== 'existente'));
    document.querySelectorAll('.familia-nueva').forEach(el => el.classList.toggle('d-none', modo !== 'nueva'));
}
document.addEventListener('DOMContentLoaded', toggleFamiliaFields);
</script>

<?php include __DIR__ . '/footer.php'; ?>
