<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/comidas_helpers.php';
ensure_comidas_multiples_schema($pdo);
require_admin();

$familiaId = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare("\n    SELECT fa.*, CONCAT(fr.nombre, ' ', fr.apellidos) AS representante\n    FROM familias fa\n    LEFT JOIN falleros fr ON fr.id = fa.representante_fallero_id\n    WHERE fa.id = :id\n    LIMIT 1\n");
$stmt->execute(['id' => $familiaId]);
$familia = $stmt->fetch();

if (!$familia) {
    http_response_code(404);
    exit('Familia no encontrada.');
}

$stmtReps = $pdo->prepare("
    SELECT fr.fallero_id, CONCAT(f.nombre, ' ', f.apellidos) AS nombre_completo
    FROM familia_representantes fr
    INNER JOIN falleros f ON f.id = fr.fallero_id
    WHERE fr.familia_id = :familia_id
    ORDER BY fr.created_at ASC, fr.fallero_id ASC
    LIMIT 2
");
$stmtReps->execute(['familia_id' => $familiaId]);
$representantesFamilia = $stmtReps->fetchAll();
$representantesIds = array_map('intval', array_column($representantesFamilia, 'fallero_id'));

$stmt = $pdo->prepare("\n    SELECT f.*, u.id AS user_id, u.is_active AS user_active\n    FROM falleros f\n    LEFT JOIN users u ON u.fallero_id = f.id\n    WHERE f.familia_id = :familia_id\n    ORDER BY f.apellidos, f.nombre\n");
$stmt->execute(['familia_id' => $familiaId]);
$miembros = $stmt->fetchAll();

$stmt = $pdo->prepare("\n    SELECT a.titulo, a.fecha, a.hora, r.estado, oc.nombre AS opcion, CONCAT(f.nombre, ' ', f.apellidos) AS fallero\n    FROM reservas r\n    INNER JOIN actos a ON a.id = r.acto_id\n    INNER JOIN falleros f ON f.id = r.fallero_id\n    LEFT JOIN opciones_comida oc ON oc.id = r.opcion_comida_id\n    WHERE f.familia_id = :familia_id\n    ORDER BY a.fecha DESC, a.hora DESC\n    LIMIT 50\n");
$stmt->execute(['familia_id' => $familiaId]);
$reservas = $stmt->fetchAll();

$page_title = 'Detalle de familia';
include __DIR__ . '/header.php';
include __DIR__ . '/sidebar.php';
?>

<main class="main-dashboard">
    <header class="dashboard-topbar">
        <button class="mobile-menu-btn" type="button" onclick="toggleSidebar()">☰</button>
        <div>
            <h1><?= e($familia['nombre']) ?></h1>
            <p>Ficha de familia y miembros asociados</p>
        </div>
        <div class="topbar-actions">
            <a href="familias.php" class="topbar-btn">← Familias</a>
            <a href="logout.php" class="topbar-btn">➤ Salir</a>
        </div>
    </header>

    <section class="dashboard-content">
        <div class="stats-grid mb-4">
            <div class="stat-box blue">
                <div class="stat-number"><?= count($miembros) ?></div>
                <div class="stat-text">Miembros</div>
                <div class="stat-link">Faller@s asociados</div>
            </div>
            <div class="stat-box green">
                <div class="stat-number"><?= count(array_filter($miembros, fn($m) => $m['estado'] === 'activo')) ?></div>
                <div class="stat-text">Activos</div>
                <div class="stat-link">Censo vigente</div>
            </div>
            <div class="stat-box amber">
                <div class="stat-number"><?= count(array_filter($miembros, fn($m) => $m['estado'] === 'pendiente')) ?></div>
                <div class="stat-text">Pendientes</div>
                <div class="stat-link">Por aprobar</div>
            </div>
            <div class="stat-box purple">
                <div class="stat-number"><?= count($reservas) ?></div>
                <div class="stat-text">Reservas</div>
                <div class="stat-link">Historial familiar</div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-xl-7">
                <div class="card-modern table-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h5 mb-0">Faller@s asociados</h2>
                        <a href="familias.php?edit=<?= (int) $familia['id'] ?>" class="btn btn-sm btn-light">Editar familia</a>
                    </div>
                    <table class="table align-middle">
                        <thead><tr><th>Fallero/a</th><th>DNI</th><th>Tipo</th><th>Estado</th><th>Usuario</th></tr></thead>
                        <tbody>
                        <?php foreach ($miembros as $m): ?>
                            <tr>
                                <td>
                                    <strong><?= e($m['nombre'] . ' ' . $m['apellidos']) ?></strong>
                                    <?php if (in_array((int)$m['id'], $representantesIds, true)): ?>
                                        <span class="badge bg-primary ms-1">representante</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= e($m['dni']) ?></td>
                                <td><?= e($m['tipo']) ?></td>
                                <td><span class="badge badge-estado <?= e($m['estado']) ?>"><?= e($m['estado']) ?></span></td>
                                <td><?= $m['user_id'] ? ((int)$m['user_active'] === 1 ? 'activo' : 'pendiente') : 'sin usuario' ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (!$miembros): ?>
                            <tr><td colspan="5" class="text-muted">Esta familia todavía no tiene falleros asociados.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-xl-5">
                <div class="card-modern mb-4">
                    <h2 class="h5 mb-3">Datos de familia</h2>
                    <div class="detail-list">
                        <div><span>Representantes</span><strong><?php if ($representantesFamilia): ?><?= implode('<br>', array_map(fn($r) => e($r['nombre_completo']), $representantesFamilia)) ?><?php else: ?><?= e($familia['representante'] ?: 'Sin representante') ?><?php endif; ?></strong></div>
                        <div><span>Observaciones</span><strong><?= e($familia['observaciones'] ?: 'Sin observaciones') ?></strong></div>
                    </div>
                </div>

                <div class="card-modern table-card">
                    <h2 class="h5 mb-3">Últimas reservas familiares</h2>
                    <table class="table align-middle">
                        <thead><tr><th>Acto</th><th>Fallero/a</th><th>Opción</th><th>Estado</th></tr></thead>
                        <tbody>
                        <?php foreach ($reservas as $r): ?>
                            <tr>
                                <td><?= e($r['titulo']) ?><br><small class="text-muted"><?= e($r['fecha']) ?> <?= e(substr((string)$r['hora'], 0, 5)) ?></small></td>
                                <td><?= e($r['fallero']) ?></td>
                                <td><?= e($r['opcion'] ?: 'Sin opción') ?></td>
                                <td><?= e($r['estado']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (!$reservas): ?>
                            <tr><td colspan="4" class="text-muted">Sin reservas familiares todavía.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/footer.php'; ?>
