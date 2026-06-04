<?php
require_once __DIR__ . '/config.php';
require_admin();
ensure_audit_columns($pdo);
$page_title = 'Reservas';

$acto_id = (int) ($_GET['acto_id'] ?? 0);
$estadoFiltro = trim($_GET['estado'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $id = (int) ($_POST['id'] ?? 0);

    if (($_POST['action'] ?? '') === 'estado') {
        $pdo->prepare("UPDATE reservas SET estado=:estado, updated_at=NOW(), updated_by=:updated_by WHERE id=:id")
            ->execute(['estado' => $_POST['estado'], 'id' => $id, 'updated_by' => current_user_id()]);
        log_activity($pdo, 'update', 'reservas', 'Estado de reserva actualizado');
    }

    if (($_POST['action'] ?? '') === 'pago') {
        $stmt = $pdo->prepare("
            SELECT r.qr_token, r.pagada, a.titulo AS acto, u.id AS user_id
            FROM reservas r
            INNER JOIN actos a ON a.id = r.acto_id
            LEFT JOIN users u ON u.fallero_id = r.fallero_id AND u.is_active = 1
            WHERE r.id = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        $actual = $stmt->fetch();

        if ($actual) {
            $yaEstabaPagada = (int) ($actual['pagada'] ?? 0) === 1;
            $token = !empty($actual['qr_token']) ? $actual['qr_token'] : bin2hex(random_bytes(32));

            $pdo->prepare("UPDATE reservas SET pagada=1, fecha_pago=COALESCE(fecha_pago, NOW()), qr_token=:token, updated_at=NOW(), updated_by=:updated_by WHERE id=:id")
                ->execute(['token' => $token, 'id' => $id, 'updated_by' => current_user_id()]);
            log_activity($pdo, 'pago', 'reservas', 'Reserva marcada como pagada');

            // Avisamos solo al usuario propietario de la reserva cuando el pago pasa de no pagado a pagado.
            if (!$yaEstabaPagada && !empty($actual['user_id'])) {
                require_once __DIR__ . '/enviar-notificacion.php';

                enviarNotificacionPush(
                    'QR disponible',
                    'Ya tienes disponible tu QR para el acto ' . $actual['acto'],
                    '/mis_reservas.php',
                    (int) $actual['user_id']
                );
            }
        }
    }

    if (($_POST['action'] ?? '') === 'no_pago') {
        $pdo->prepare("UPDATE reservas SET pagada=0, fecha_pago=NULL, qr_token=NULL, qr_usado=0, fecha_qr_usado=NULL, validado_por=NULL, updated_at=NOW(), updated_by=:updated_by WHERE id=:id")
            ->execute(['id' => $id, 'updated_by' => current_user_id()]);
        log_activity($pdo, 'pago', 'reservas', 'Reserva marcada como no pagada');
    }

    redirect('reservas.php' . ($acto_id ? '?acto_id=' . $acto_id : ''));
}

$actos = $pdo->query("SELECT id, titulo FROM actos ORDER BY fecha DESC")->fetchAll();

$sql = "
    SELECT r.*, a.titulo AS acto, CONCAT(f.apellidos, ', ', f.nombre) AS fallero, CONCAT(f.nombre, ' ', f.apellidos) AS fallero_nombre_completo, oc.nombre AS opcion
    FROM reservas r
    JOIN actos a ON a.id = r.acto_id
    JOIN falleros f ON f.id = r.fallero_id
    LEFT JOIN opciones_comida oc ON oc.id = r.opcion_comida_id
";
$params = [];
$where = [];
if ($acto_id) { $where[] = "r.acto_id = :acto_id"; $params['acto_id'] = $acto_id; }
if ($estadoFiltro !== '') { $where[] = "r.estado = :estado"; $params['estado'] = $estadoFiltro; }
if ($where) { $sql .= " WHERE " . implode(" AND ", $where); }
$sql .= " ORDER BY r.fecha_reserva DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$reservasBase = $stmt->fetchAll();

$invitadosPorReserva = [];
$reservaIds = array_map(static fn($r) => (int)$r['id'], $reservasBase);
if ($reservaIds) {
    $ph = implode(',', array_fill(0, count($reservaIds), '?'));
    $stmtInv = $pdo->prepare("SELECT ri.*, oc.nombre AS opcion FROM reserva_invitados ri LEFT JOIN opciones_comida oc ON oc.id = ri.opcion_comida_id WHERE ri.reserva_id IN ($ph) ORDER BY ri.id ASC");
    $stmtInv->execute($reservaIds);
    foreach ($stmtInv->fetchAll() as $inv) {
        $invitadosPorReserva[(int)$inv['reserva_id']][] = $inv;
    }
}

// Convertimos los invitados en líneas independientes para que se vean y cuenten como reservas reales.
$lineas = [];
foreach ($reservasBase as $reserva) {
    $reserva['_tipo_linea'] = 'fallero';
    $reserva['_nombre_linea'] = $reserva['fallero'];
    $reserva['_opcion_linea'] = $reserva['opcion'] ?: 'Sin opción';
    $reserva['_invitado_de'] = '-';
    $lineas[] = $reserva;

    foreach (($invitadosPorReserva[(int)$reserva['id']] ?? []) as $inv) {
        $lineas[] = [
            '_tipo_linea' => 'invitado',
            '_nombre_linea' => trim(($inv['nombre'] ?? '') . (!empty($inv['tipo']) ? ' · ' . $inv['tipo'] : '')),
            '_opcion_linea' => $inv['opcion'] ?: 'Sin opción',
            '_invitado_de' => $reserva['fallero_nombre_completo'],
            'acto' => $reserva['acto'],
            'estado' => $reserva['estado'],
            'pagada' => $reserva['pagada'],
            'qr_usado' => $reserva['qr_usado'],
            'id' => $reserva['id'],
            'fecha_reserva' => $reserva['fecha_reserva'],
        ];
    }
}

$totales = [];
if ($acto_id) {
    $stmt = $pdo->prepare("
        SELECT opcion, SUM(total) AS total
        FROM (
            SELECT COALESCE(oc.nombre, 'Sin opción') AS opcion, COUNT(*) AS total
            FROM reservas r
            LEFT JOIN opciones_comida oc ON oc.id = r.opcion_comida_id
            WHERE r.acto_id = :acto_id AND r.estado = 'confirmada'
            GROUP BY oc.nombre
            UNION ALL
            SELECT COALESCE(oc2.nombre, 'Sin opción') AS opcion, COUNT(*) AS total
            FROM reserva_invitados ri
            INNER JOIN reservas r2 ON r2.id = ri.reserva_id
            LEFT JOIN opciones_comida oc2 ON oc2.id = ri.opcion_comida_id
            WHERE r2.acto_id = :acto_id2 AND r2.estado = 'confirmada'
            GROUP BY oc2.nombre
        ) x
        GROUP BY opcion
        ORDER BY total DESC
    ");
    $stmt->execute(['acto_id' => $acto_id, 'acto_id2' => $acto_id]);
    $totales = $stmt->fetchAll();
}

include __DIR__ . '/header.php';
include __DIR__ . '/sidebar.php';
?>
<main class="main-dashboard">
<header class="dashboard-topbar"><button class="mobile-menu-btn" type="button" onclick="toggleSidebar()">☰</button><div><h1>Reservas</h1><p>Gestión de reservas, pagos y códigos QR</p></div><div class="topbar-actions"><a href="index.php" class="topbar-btn">← Panel</a><a href="logout.php" class="topbar-btn">➤ Salir</a></div></header>
<section class="dashboard-content">
<div class="card-modern mb-4">
<form class="row g-2 align-items-end">
<div class="col-md-6"><label class="form-label">Filtrar por acto</label><select class="form-select" name="acto_id"><option value="0">Todos los actos</option><?php foreach ($actos as $acto): ?><option value="<?= $acto['id'] ?>" <?= $acto_id === (int)$acto['id'] ? 'selected' : '' ?>><?= e($acto['titulo']) ?></option><?php endforeach; ?></select></div>
<div class="col-auto"><button class="btn btn-primary">Filtrar</button></div><div class="col-auto"><a class="btn btn-light" href="reservas.php">Limpiar</a></div>
<?php if ($acto_id): ?><div class="col-auto"><a class="btn btn-success" href="exportar_reservas.php?acto_id=<?= (int)$acto_id ?>">Descargar reservas</a></div><?php endif; ?>
</form>
</div>
<?php if ($totales): ?><div class="stat-grid mb-4"><?php foreach ($totales as $total): ?><div class="card-modern stat-card"><div class="stat-icon">🍽️</div><div><p class="stat-label"><?= e($total['opcion']) ?></p><p class="stat-value"><?= e((string)$total['total']) ?></p></div></div><?php endforeach; ?></div><?php endif; ?>
<div class="card-modern table-card"><table class="table align-middle"><thead><tr><th>Persona</th><th>Acto</th><th>Opción</th><th>Invitado de</th><th>Estado</th><th>Pago</th><th>QR</th><th></th></tr></thead><tbody>
<?php foreach ($lineas as $reserva): ?>
<tr class="<?= ($reserva['_tipo_linea'] ?? '') === 'invitado' ? 'table-light' : '' ?>"><td><strong><?= e($reserva['_nombre_linea']) ?></strong><?php if (($reserva['_tipo_linea'] ?? '') === 'invitado'): ?><br><span class="badge bg-info text-dark">Invitado/a</span><?php endif; ?></td><td><?= e($reserva['acto']) ?></td><td><?= e($reserva['_opcion_linea']) ?></td><td><?= e($reserva['_invitado_de']) ?></td><td><?= e($reserva['estado']) ?></td><td><?= (int)$reserva['pagada'] ? '<span class="badge bg-success">Pagada</span>' : '<span class="badge bg-secondary">No pagada</span>' ?></td><td><?= (int)$reserva['qr_usado'] ? '<span class="badge bg-danger">Usado</span>' : ((int)$reserva['pagada'] ? '<span class="badge bg-success">Disponible</span>' : '-') ?></td><td class="text-end">
<?php if (($reserva['_tipo_linea'] ?? '') === 'fallero'): ?>
<form method="post" class="d-inline"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="estado"><input type="hidden" name="id" value="<?= (int)$reserva['id'] ?>"><select class="form-select form-select-sm d-inline w-auto" name="estado"><?php foreach (['confirmada','cancelada','pendiente'] as $estado): ?><option value="<?= $estado ?>" <?= $reserva['estado'] === $estado ? 'selected' : '' ?>><?= $estado ?></option><?php endforeach; ?></select><button class="btn btn-sm btn-light">Guardar</button></form>
<form method="post" class="d-inline"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)$reserva['id'] ?>"><input type="hidden" name="action" value="<?= (int)$reserva['pagada'] ? 'no_pago' : 'pago' ?>"><button class="btn btn-sm <?= (int)$reserva['pagada'] ? 'btn-outline-danger' : 'btn-success' ?>"><?= (int)$reserva['pagada'] ? 'Quitar pago' : 'Marcar pagada' ?></button></form>
<?php else: ?>
<span class="text-muted small">Incluido en la reserva</span>
<?php endif; ?>
</td></tr>
<?php endforeach; ?>
<?php if (!$lineas): ?><tr><td colspan="8" class="text-muted">No hay reservas.</td></tr><?php endif; ?>
</tbody></table></div>
</section></main><?php include __DIR__ . '/footer.php'; ?>
