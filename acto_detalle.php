<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/comidas_helpers.php';
ensure_comidas_multiples_schema($pdo);
require_login();

$actoId = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM actos WHERE id=:id LIMIT 1");
$stmt->execute(['id' => $actoId]);
$acto = $stmt->fetch();

if (!$acto) {
    http_response_code(404);
    exit('Acto no encontrado.');
}

$page_title = 'Detalle del acto';

$stmt = $pdo->prepare("
    SELECT
        (SELECT COUNT(*) FROM reservas WHERE acto_id=:acto_id AND estado='confirmada') +
        (SELECT COUNT(*) FROM reserva_invitados ri INNER JOIN reservas r ON r.id=ri.reserva_id WHERE r.acto_id=:acto_id2 AND r.estado='confirmada')
");
$stmt->execute(['acto_id' => $actoId, 'acto_id2' => $actoId]);
$totalConfirmadas = (int) $stmt->fetchColumn();

$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM reservas
    WHERE acto_id=:acto_id AND estado='cancelada'
");
$stmt->execute(['acto_id' => $actoId]);
$totalCanceladas = (int) $stmt->fetchColumn();

$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM reservas
    WHERE acto_id=:acto_id AND estado='pendiente'
");
$stmt->execute(['acto_id' => $actoId]);
$totalPendientes = (int) $stmt->fetchColumn();

$stmt = $pdo->prepare("
    SELECT oc.id,
           oc.categoria,
           oc.nombre,
           oc.descripcion,
           oc.max_plazas,
           oc.is_active,
           (
               SELECT COUNT(*) FROM reserva_opciones ro INNER JOIN reservas r ON r.id = ro.reserva_id
               WHERE ro.opcion_comida_id = oc.id AND r.estado='confirmada'
           ) + (
               SELECT COUNT(*) FROM reserva_invitado_opciones rio INNER JOIN reserva_invitados ri ON ri.id = rio.reserva_invitado_id
               INNER JOIN reservas r2 ON r2.id = ri.reserva_id
               WHERE rio.opcion_comida_id = oc.id AND r2.estado='confirmada'
           ) AS total_confirmadas
    FROM opciones_comida oc
    WHERE oc.acto_id = :acto_id
    ORDER BY oc.categoria ASC, oc.id ASC
");
$stmt->execute(['acto_id' => $actoId]);
$opciones = $stmt->fetchAll();

$opcionesPorBloque = [];
foreach ($opciones as $opcionResumen) {
    $bloque = trim((string)($opcionResumen['categoria'] ?? '')) ?: 'Comida';
    if (!isset($opcionesPorBloque[$bloque])) {
        $opcionesPorBloque[$bloque] = [];
    }
    $opcionesPorBloque[$bloque][] = $opcionResumen;
}

$stmt = $pdo->prepare("
    SELECT r.*,
           f.nombre,
           f.apellidos,
           f.dni,
           oc.nombre AS opcion, oci.nombre AS invitado_opcion_nombre
    FROM reservas r
    INNER JOIN falleros f ON f.id = r.fallero_id
    LEFT JOIN opciones_comida oc ON oc.id = r.opcion_comida_id LEFT JOIN opciones_comida oci ON oci.id = r.invitado_opcion_comida_id
    WHERE r.acto_id = :acto_id
    ORDER BY r.estado ASC, f.apellidos ASC, f.nombre ASC
");
$stmt->execute(['acto_id' => $actoId]);
$reservas = $stmt->fetchAll();

$invitadosPorReserva = [];
$reservaIds = array_map(static fn($r) => (int)$r['id'], $reservas);
if ($reservaIds) {
    $ph = implode(',', array_fill(0, count($reservaIds), '?'));
    $stmtInv = $pdo->prepare("SELECT ri.*, oc.nombre AS opcion_nombre FROM reserva_invitados ri LEFT JOIN opciones_comida oc ON oc.id=ri.opcion_comida_id WHERE ri.reserva_id IN ($ph) ORDER BY ri.id ASC");
    $stmtInv->execute($reservaIds);
    foreach ($stmtInv->fetchAll() as $inv) {
        $invitadosPorReserva[(int)$inv['reserva_id']][] = $inv;
    }
}

$opcionesTextoPorReserva = [];
$opcionesTextoPorInvitado = [];
if ($reservaIds) {
    $ph = implode(',', array_fill(0, count($reservaIds), '?'));
    $stmtRO = $pdo->prepare("SELECT ro.reserva_id, ro.categoria, oc.nombre FROM reserva_opciones ro INNER JOIN opciones_comida oc ON oc.id=ro.opcion_comida_id WHERE ro.reserva_id IN ($ph) ORDER BY ro.categoria, oc.id");
    $stmtRO->execute($reservaIds);
    foreach ($stmtRO->fetchAll() as $ro) $opcionesTextoPorReserva[(int)$ro['reserva_id']][] = $ro['categoria'] . ': ' . $ro['nombre'];

    $invIds = [];
    foreach ($invitadosPorReserva as $lista) foreach ($lista as $invTmp) $invIds[] = (int)$invTmp['id'];
    if ($invIds) {
        $iph = implode(',', array_fill(0, count($invIds), '?'));
        $stmtIO = $pdo->prepare("SELECT rio.reserva_invitado_id, rio.categoria, oc.nombre FROM reserva_invitado_opciones rio INNER JOIN opciones_comida oc ON oc.id=rio.opcion_comida_id WHERE rio.reserva_invitado_id IN ($iph) ORDER BY rio.categoria, oc.id");
        $stmtIO->execute($invIds);
        foreach ($stmtIO->fetchAll() as $io) $opcionesTextoPorInvitado[(int)$io['reserva_invitado_id']][] = $io['categoria'] . ': ' . $io['nombre'];
    }
}

$lineasReserva = [];
foreach ($reservas as $reserva) {
    $lineasReserva[] = [
        'tipo' => 'fallero',
        'persona' => trim($reserva['nombre'] . ' ' . $reserva['apellidos']),
        'dni' => $reserva['dni'],
        'opcion' => !empty($opcionesTextoPorReserva[(int)$reserva['id']]) ? implode(' · ', $opcionesTextoPorReserva[(int)$reserva['id']]) : ($reserva['opcion'] ?: 'Sin opción'),
        'invitado_de' => '-',
        'estado' => $reserva['estado'],
        'fecha_reserva' => $reserva['fecha_reserva'],
    ];
    foreach (($invitadosPorReserva[(int)$reserva['id']] ?? []) as $inv) {
        $lineasReserva[] = [
            'tipo' => 'invitado',
            'persona' => trim(($inv['nombre'] ?? '') . (!empty($inv['tipo']) ? ' · ' . $inv['tipo'] : '')),
            'dni' => '',
            'opcion' => !empty($opcionesTextoPorInvitado[(int)$inv['id']]) ? implode(' · ', $opcionesTextoPorInvitado[(int)$inv['id']]) : ($inv['opcion_nombre'] ?: 'Sin opción'),
            'invitado_de' => trim($reserva['nombre'] . ' ' . $reserva['apellidos']),
            'estado' => $reserva['estado'],
            'fecha_reserva' => $reserva['fecha_reserva'],
        ];
    }
}

include __DIR__ . '/header.php';
include __DIR__ . '/sidebar.php';
?>

<main class="main-dashboard">
    <header class="dashboard-topbar">
        <button class="mobile-menu-btn" type="button" onclick="toggleSidebar()">☰</button>
        <div>
            <h1><?= e($acto['titulo']) ?></h1>
            <p><?= e($acto['fecha']) ?> <?= e(substr((string)$acto['hora'], 0, 5)) ?> · <?= e($acto['ubicacion'] ?: 'Sin ubicación') ?></p>
        </div>
        <div class="topbar-actions">
            <a href="<?= is_admin() ? 'actos.php' : 'mis_actos.php' ?>" class="topbar-btn">← Actos</a>
            <?php if (is_admin()): ?>
                <a href="actos.php?edit=<?= (int) $acto['id'] ?>" class="topbar-btn">Editar</a>
            <?php endif; ?>
        </div>
    </header>

    <section class="dashboard-content">
        <div class="stats-grid mb-4">
            <div class="stat-box blue">
                <div class="stat-number"><?= $totalConfirmadas + $totalCanceladas + $totalPendientes ?></div>
                <div class="stat-text">Reservas totales</div>
                <div class="stat-link">Todas las inscripciones</div>
            </div>

            <div class="stat-box green">
                <div class="stat-number"><?= $totalConfirmadas ?></div>
                <div class="stat-text">Confirmadas</div>
                <div class="stat-link">Asistencia prevista</div>
            </div>

            <div class="stat-box amber">
                <div class="stat-number"><?= $totalPendientes ?></div>
                <div class="stat-text">Pendientes</div>
                <div class="stat-link">Por revisar</div>
            </div>

            <div class="stat-box red">
                <div class="stat-number"><?= $totalCanceladas ?></div>
                <div class="stat-text">Canceladas</div>
                <div class="stat-link">No cuentan</div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-xl-5">
                <div class="card-modern mb-4">
                    <h2 class="h5 mb-3">Recuento por bloques</h2>

                    <?php if (!$opcionesPorBloque): ?>
                        <p class="text-muted mb-0">Este acto todavía no tiene opciones internas configuradas.</p>
                    <?php endif; ?>

                    <?php if ($opcionesPorBloque): ?>
                        <div class="acto-bloques-resumen">
                            <?php foreach ($opcionesPorBloque as $bloque => $opcionesBloque): ?>
                                <section class="reservas-bloque-card acto-bloque-card">
                                    <div class="reservas-bloque-head">
                                        <div class="stat-icon">🍽️</div>
                                        <h2><?= e($bloque) ?></h2>
                                    </div>
                                    <div class="reservas-bloque-opciones">
                                        <?php foreach ($opcionesBloque as $opcion): ?>
                                            <?php
                                            $max = $opcion['max_plazas'] !== null ? (int) $opcion['max_plazas'] : null;
                                            $total = (int) $opcion['total_confirmadas'];
                                            $percent = $max && $max > 0 ? min(100, round(($total / $max) * 100)) : 0;
                                            ?>
                                            <div class="reservas-bloque-opcion acto-bloque-opcion">
                                                <div>
                                                    <span><?= e($opcion['nombre']) ?></span>
                                                    <?php if (!(int) $opcion['is_active']): ?>
                                                        <span class="badge bg-secondary ms-1">inactiva</span>
                                                    <?php endif; ?>
                                                    <?php if ($opcion['descripcion']): ?>
                                                        <div class="text-muted small mt-1"><?= e($opcion['descripcion']) ?></div>
                                                    <?php endif; ?>
                                                    <?php if ($max): ?>
                                                        <div class="progress mt-2" style="height: 8px;">
                                                            <div class="progress-bar" style="width: <?= $percent ?>%"></div>
                                                        </div>
                                                        <div class="text-muted small mt-1"><?= $total ?> de <?= $max ?> plazas</div>
                                                    <?php else: ?>
                                                        <div class="text-muted small mt-1">Sin límite de plazas específico</div>
                                                    <?php endif; ?>
                                                </div>
                                                <strong><?= $total ?></strong>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </section>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="card-modern">
                    <?php if (!empty($acto['imagen'])): ?>
                        <img src="<?= e($acto['imagen']) ?>" alt="<?= e($acto['titulo']) ?>" style="width:100%;height:230px;object-fit:cover;border-radius:18px;margin-bottom:18px;">
                    <?php endif; ?>
                    <h2 class="h5 mb-3">Datos del acto</h2>
                    <div class="detail-list">
                        <div><span>Tipo</span><strong><?= e($acto['tipo']) ?></strong></div>
                        <div><span>Estado</span><strong><?= e($acto['estado']) ?></strong></div>
                        <div><span>Plazas máximas</span><strong><?= e((string)($acto['max_plazas'] ?? 'Sin límite')) ?></strong></div>
                        <div><span>Descripción</span><strong><?= e($acto['descripcion'] ?: 'Sin descripción') ?></strong></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-7">
                <div class="card-modern table-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h2 class="h5 mb-0">Faller@s apuntados</h2>
                        <?php if (is_admin()): ?><div class="d-flex gap-2"><a href="reservas.php?acto_id=<?= (int)$actoId ?>" class="btn btn-sm btn-light">Gestionar reservas</a><a href="exportar_reservas.php?acto_id=<?= (int)$actoId ?>" class="btn btn-sm btn-success">Descargar reservas</a></div><?php endif; ?>
                    </div>

                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Persona</th>
                                <?php if (is_admin()): ?><th>DNI</th><?php endif; ?>
                                <th>Opción</th>
                                <th>Invitado de</th>
                                <th>Estado</th>
                                <th>Fecha reserva</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lineasReserva as $reserva): ?>
                                <tr class="<?= $reserva['tipo'] === 'invitado' ? 'table-light' : '' ?>">
                                    <td><strong><?= e($reserva['persona']) ?></strong><?php if ($reserva['tipo'] === 'invitado'): ?><br><span class="badge bg-info text-dark">Invitado/a</span><?php endif; ?></td>
                                    <?php if (is_admin()): ?><td><?= e($reserva['dni'] ?: '-') ?></td><?php endif; ?>
                                    <td><?= e($reserva['opcion']) ?></td>
                                    <td><?= e($reserva['invitado_de']) ?></td>
                                    <td><span class="badge badge-estado <?= e($reserva['estado']) ?>"><?= e($reserva['estado']) ?></span></td>
                                    <td><?= e(substr((string)$reserva['fecha_reserva'], 0, 16)) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (!$lineasReserva): ?>
                                <tr>
                                    <td colspan="<?= is_admin() ? 6 : 5 ?>" class="text-muted">Todavía no hay reservas para este acto.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/footer.php'; ?>
