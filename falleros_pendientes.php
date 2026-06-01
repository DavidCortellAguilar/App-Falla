<?php
require_once __DIR__ . '/config.php';
require_admin();
$page_title = 'Solicitudes pendientes';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();

    $action = $_POST['action'] ?? '';
    $falleroId = (int) ($_POST['fallero_id'] ?? 0);

    if ($falleroId > 0 && in_array($action, ['approve', 'reject'], true)) {
        if ($action === 'approve') {
            $pdo->prepare("UPDATE falleros SET estado='activo', updated_at=NOW() WHERE id=:id")
                ->execute(['id' => $falleroId]);
            $pdo->prepare("UPDATE users SET is_active=1, updated_at=NOW() WHERE fallero_id=:id")
                ->execute(['id' => $falleroId]);

            $stmt = $pdo->prepare("SELECT familia_id FROM falleros WHERE id=:id");
            $stmt->execute(['id' => $falleroId]);
            $familiaId = $stmt->fetchColumn();
            if ($familiaId) {
                $pdo->prepare("UPDATE familias SET updated_at=NOW() WHERE id=:id")
                    ->execute(['id' => (int) $familiaId]);
            }

            log_activity($pdo, 'approve', 'falleros', 'Solicitud de fallero aprobada');
        } else {
            $pdo->prepare("UPDATE falleros SET estado='baja', updated_at=NOW() WHERE id=:id")
                ->execute(['id' => $falleroId]);
            $pdo->prepare("UPDATE users SET is_active=0, updated_at=NOW() WHERE fallero_id=:id")
                ->execute(['id' => $falleroId]);
            log_activity($pdo, 'reject', 'falleros', 'Solicitud de fallero rechazada');
        }
    }

    redirect('falleros_pendientes.php');
}

$pendientes = $pdo->query("\n    SELECT f.*, fa.nombre AS familia,\n           CASE WHEN fa.representante_fallero_id = f.id THEN 1 ELSE 0 END AS es_representante,\n           u.id AS user_id, u.is_active AS user_active\n    FROM falleros f\n    LEFT JOIN familias fa ON fa.id = f.familia_id\n    LEFT JOIN users u ON u.fallero_id = f.id\n    WHERE f.estado = 'pendiente' OR u.is_active = 0\n    ORDER BY f.created_at DESC\n")->fetchAll();

include __DIR__ . '/header.php';
include __DIR__ . '/sidebar.php';
?>

<main class="main-dashboard">
    <header class="dashboard-topbar">
        <button class="mobile-menu-btn" type="button" onclick="toggleSidebar()">☰</button>
        <div>
            <h1>Solicitudes pendientes</h1>
            <p>Altas de nuevos falleros y familias pendientes de aprobación</p>
        </div>
        <div class="topbar-actions">
            <a href="index.php" class="topbar-btn">← Panel</a>
            <a href="logout.php" class="topbar-btn">➤ Salir</a>
        </div>
    </header>

    <section class="dashboard-content">
        <div class="card-modern table-card">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Solicitante</th>
                        <th>DNI</th>
                        <th>Contacto</th>
                        <th>Familia</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($pendientes as $p): ?>
                    <tr>
                        <td><strong><?= e($p['nombre'] . ' ' . $p['apellidos']) ?></strong></td>
                        <td><?= e($p['dni']) ?></td>
                        <td>
                            <?= e($p['telefono'] ?: '-') ?><br>
                            <small class="text-muted"><?= e($p['email'] ?: '') ?></small>
                        </td>
                        <td>
                            <?= e($p['familia'] ?: 'Sin familia') ?>
                            <?php if ((int) $p['es_representante'] === 1): ?>
                                <span class="badge bg-primary ms-1">representante</span>
                            <?php endif; ?>
                        </td>
                        <td><?= e($p['tipo']) ?></td>
                        <td><span class="badge badge-estado pendiente">pendiente</span></td>
                        <td class="text-end">
                            <form method="post" class="d-inline">
                                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                <input type="hidden" name="fallero_id" value="<?= (int) $p['id'] ?>">
                                <input type="hidden" name="action" value="approve">
                                <button class="btn btn-sm btn-success">Aprobar</button>
                            </form>
                            <form method="post" class="d-inline" onsubmit="return confirm('¿Rechazar esta solicitud?')">
                                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                <input type="hidden" name="fallero_id" value="<?= (int) $p['id'] ?>">
                                <input type="hidden" name="action" value="reject">
                                <button class="btn btn-sm btn-outline-danger">Rechazar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$pendientes): ?>
                    <tr><td colspan="7" class="text-muted">No hay solicitudes pendientes.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<?php include __DIR__ . '/footer.php'; ?>
