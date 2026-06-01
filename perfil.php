<?php
require_once __DIR__ . '/config.php';
require_login();
$page_title = 'Mi perfil';

$stmt = $pdo->prepare("
    SELECT f.*, fa.nombre AS familia
    FROM falleros f
    LEFT JOIN familias fa ON fa.id = f.familia_id
    WHERE f.id = :id
");
$stmt->execute(['id' => $_SESSION['fallero_id'] ?? 0]);
$fallero = $stmt->fetch();

include __DIR__ . '/header.php';
include __DIR__ . '/sidebar.php';
?>

<main class="main-dashboard">
    <header class="dashboard-topbar">
        <button class="mobile-menu-btn" type="button" onclick="toggleSidebar()">☰</button>
        <div>
            <h1>Mi perfil</h1>
            <p>Datos personales del faller@</p>
        </div>
        <div class="topbar-actions">
            <a href="index.php" class="topbar-btn">← Panel</a>
            <a href="logout.php" class="topbar-btn">➤ Salir</a>
        </div>
    </header>

    <section class="dashboard-content">

        <div class="card-modern">
            <h2 class="h5 mb-3">Datos personales</h2>

            <?php if (!$fallero): ?>
                <div class="alert alert-warning">Este usuario no tiene ficha de fallero asociada.</div>
            <?php else: ?>
                <div class="row g-3">
                    <div class="col-md-6"><strong>Nombre:</strong><br><?= e($fallero['nombre'] . ' ' . $fallero['apellidos']) ?></div>
                    <div class="col-md-6"><strong>DNI:</strong><br><?= e($fallero['dni']) ?></div>
                    <div class="col-md-6"><strong>Teléfono:</strong><br><?= e($fallero['telefono']) ?></div>
                    <div class="col-md-6"><strong>Email:</strong><br><?= e($fallero['email']) ?></div>
                    <div class="col-md-6"><strong>Tipo:</strong><br><?= e($fallero['tipo']) ?></div>
                    <div class="col-md-6"><strong>Estado:</strong><br><?= e($fallero['estado']) ?></div>
                    <div class="col-md-6"><strong>Familia:</strong><br><?= e($fallero['familia']) ?></div>
                    <div class="col-md-6"><strong>Dirección:</strong><br><?= e($fallero['direccion']) ?></div>
                </div>
            <?php endif; ?>
        </div>

        <div class="card-modern mt-4">
            <div class="profile-card-heading">
                <div class="profile-icon-bubble">🔔</div>
                <div>
                    <h2 class="h5 mb-1">Notificaciones</h2>
                    <p class="text-muted mb-0">
                        Activa los avisos en tu móvil para recibir nuevos actos, avisos y recordatorios de la falla.
                    </p>
                </div>
            </div>

            <div class="mt-3">
                <button id="activarNotificaciones" class="btn btn-primary" type="button">
                    Activar notificaciones
                </button>
            </div>
        </div>
        <div class="alert alert-light border mt-4">Tus datos personales quedan bloqueados tras enviar la inscripción. Para corregir cualquier dato, contacta con administración.</div>

    </section>
</main>

<?php include __DIR__ . '/footer.php'; ?>