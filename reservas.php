<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/comidas_helpers.php';
ensure_comidas_multiples_schema($pdo);
require_admin();
ensure_audit_columns($pdo);
$page_title = 'Reservas';

$acto_id = (int) ($_GET['acto_id'] ?? 0);
$estadoFiltro = trim($_GET['estado'] ?? '');
$busquedaFallero = trim($_GET['q'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $action = $_POST['action'] ?? '';
    $id = (int) ($_POST['id'] ?? 0);

    if ($action === 'bulk_pago') {
        $ids = array_values(array_unique(array_filter(array_map('intval', $_POST['reserva_ids'] ?? []))));

        if ($ids) {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $stmt = $pdo->prepare("
                SELECT r.id, r.qr_token, r.pagada, a.titulo AS acto, u.id AS user_id
                FROM reservas r
                INNER JOIN actos a ON a.id = r.acto_id
                LEFT JOIN users u ON u.fallero_id = r.fallero_id AND u.is_active = 1
                WHERE r.id IN ($placeholders)
            "
            );
            $stmt->execute($ids);
            $reservasPago = $stmt->fetchAll();

            $stmtUpdatePago = $pdo->prepare("
                UPDATE reservas
                SET pagada=1,
                    fecha_pago=COALESCE(fecha_pago, NOW()),
                    qr_token=:token,
                    updated_at=NOW(),
                    updated_by=:updated_by
                WHERE id=:id
            "
            );

            foreach ($reservasPago as $reservaPago) {
                $yaEstabaPagada = (int) ($reservaPago['pagada'] ?? 0) === 1;
                $token = !empty($reservaPago['qr_token']) ? $reservaPago['qr_token'] : bin2hex(random_bytes(32));

                $stmtUpdatePago->execute([
                    'token' => $token,
                    'id' => (int) $reservaPago['id'],
                    'updated_by' => current_user_id(),
                ]);

                if (!$yaEstabaPagada && !empty($reservaPago['user_id'])) {
                    require_once __DIR__ . '/enviar-notificacion.php';
                    enviarNotificacionPush(
                        'QR disponible',
                        'Ya tienes disponible tu QR para el acto ' . $reservaPago['acto'],
                        '/mis_reservas.php',
                        (int) $reservaPago['user_id']
                    );
                }
            }

            log_activity($pdo, 'pago', 'reservas', 'Reservas marcadas como pagadas en bloque');
        }
    }

    if ($action === 'estado') {
        $pdo->prepare("UPDATE reservas SET estado=:estado, updated_at=NOW(), updated_by=:updated_by WHERE id=:id")
            ->execute(['estado' => $_POST['estado'], 'id' => $id, 'updated_by' => current_user_id()]);
        log_activity($pdo, 'update', 'reservas', 'Estado de reserva actualizado');
    }

    if ($action === 'pago') {
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

    if ($action === 'no_pago') {
        $pdo->prepare("UPDATE reservas SET pagada=0, fecha_pago=NULL, qr_token=NULL, qr_usado=0, fecha_qr_usado=NULL, validado_por=NULL, updated_at=NOW(), updated_by=:updated_by WHERE id=:id")
            ->execute(['id' => $id, 'updated_by' => current_user_id()]);
        try { $pdo->prepare("DELETE FROM qr_validaciones_bloques WHERE reserva_id=:id")->execute(['id' => $id]); } catch (Throwable $e) {}
        log_activity($pdo, 'pago', 'reservas', 'Reserva marcada como no pagada');
    }

    $query = [];
    if ($acto_id) { $query['acto_id'] = $acto_id; }
    if ($estadoFiltro !== '') { $query['estado'] = $estadoFiltro; }
    if ($busquedaFallero !== '') { $query['q'] = $busquedaFallero; }
    redirect('reservas.php' . ($query ? '?' . http_build_query($query) : ''));
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
if ($estadoFiltro !== '') {
    $where[] = "r.estado = :estado";
    $params['estado'] = $estadoFiltro;
} else {
    // Las reservas canceladas por falleros o administradores no se muestran en el panel.
    $where[] = "r.estado <> 'cancelada'";
}
if ($busquedaFallero !== '') {
    $where[] = "(f.nombre LIKE :busqueda_fallero OR f.apellidos LIKE :busqueda_fallero OR CONCAT(f.nombre, ' ', f.apellidos) LIKE :busqueda_fallero OR CONCAT(f.apellidos, ', ', f.nombre) LIKE :busqueda_fallero)";
    $params['busqueda_fallero'] = '%' . $busquedaFallero . '%';
}
if ($where) { $sql .= " WHERE " . implode(" AND ", $where); }
$sql .= " ORDER BY r.fecha_reserva DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$reservasBase = $stmt->fetchAll();

$invitadosPorReserva = [];
$reservaIds = array_map(static fn($r) => (int)$r['id'], $reservasBase);
$opcionesPorReserva = opciones_texto_por_reserva_ids($pdo, $reservaIds);
$invitadoIds = [];
if ($reservaIds) {
    $ph = implode(',', array_fill(0, count($reservaIds), '?'));
    $stmtInv = $pdo->prepare("SELECT ri.*, oc.nombre AS opcion FROM reserva_invitados ri LEFT JOIN opciones_comida oc ON oc.id = ri.opcion_comida_id WHERE ri.reserva_id IN ($ph) ORDER BY ri.id ASC");
    $stmtInv->execute($reservaIds);
    foreach ($stmtInv->fetchAll() as $inv) {
        $invitadosPorReserva[(int)$inv['reserva_id']][] = $inv;
        $invitadoIds[] = (int)$inv['id'];
    }
}
$opcionesPorInvitado = opciones_texto_por_invitado_ids($pdo, $invitadoIds);

// Convertimos los invitados en líneas independientes para que se vean y cuenten como reservas reales.
$lineas = [];
foreach ($reservasBase as $reserva) {
    $reserva['_tipo_linea'] = 'fallero';
    $reserva['_nombre_linea'] = $reserva['fallero'];
    $reserva['_opcion_lineas'] = opciones_texto_fallback($opcionesPorReserva[(int)$reserva['id']] ?? [], $reserva['opcion'] ?? '');
    $reserva['_invitado_de'] = '-';
    $lineas[] = $reserva;

    foreach (($invitadosPorReserva[(int)$reserva['id']] ?? []) as $inv) {
        $lineas[] = [
            '_tipo_linea' => 'invitado',
            '_nombre_linea' => trim(($inv['nombre'] ?? '') . (!empty($inv['tipo']) ? ' · ' . $inv['tipo'] : '')),
            '_opcion_lineas' => opciones_texto_fallback($opcionesPorInvitado[(int)$inv['id']] ?? [], $inv['opcion'] ?? ''),
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


$totalesPorBloque = [];
if ($acto_id) {
    // Resumen por bloques: muestra todos los bloques/opciones del acto, aunque tengan 0 reservas.
    try {
        $stmtOpciones = $pdo->prepare("
            SELECT id, nombre, COALESCE(NULLIF(TRIM(categoria), ''), 'Comida') AS categoria
            FROM opciones_comida
            WHERE acto_id = :acto_id AND is_active = 1
            ORDER BY categoria ASC, id ASC
        ");
        $stmtOpciones->execute(['acto_id' => $acto_id]);

        $opcionCategoria = [];
        foreach ($stmtOpciones->fetchAll() as $opcion) {
            $categoria = trim((string)($opcion['categoria'] ?? '')) ?: 'Comida';
            $nombre = trim((string)($opcion['nombre'] ?? ''));
            $opcionId = (int)$opcion['id'];

            if (!isset($totalesPorBloque[$categoria])) {
                $totalesPorBloque[$categoria] = [];
            }

            $totalesPorBloque[$categoria][$opcionId] = [
                'nombre' => $nombre,
                'total' => 0,
            ];
            $opcionCategoria[$opcionId] = $categoria;
        }

        // Sistema nuevo: opciones por bloque de reservas principales.
        try {
            $stmtConteoReservas = $pdo->prepare("
                SELECT ro.opcion_comida_id, COUNT(*) AS total
                FROM reserva_opciones ro
                INNER JOIN reservas r ON r.id = ro.reserva_id
                WHERE r.acto_id = :acto_id AND r.estado = 'confirmada'
                GROUP BY ro.opcion_comida_id
            ");
            $stmtConteoReservas->execute(['acto_id' => $acto_id]);
            foreach ($stmtConteoReservas->fetchAll() as $row) {
                $opcionId = (int)($row['opcion_comida_id'] ?? 0);
                $categoria = $opcionCategoria[$opcionId] ?? null;
                if ($categoria !== null && isset($totalesPorBloque[$categoria][$opcionId])) {
                    $totalesPorBloque[$categoria][$opcionId]['total'] += (int)$row['total'];
                }
            }
        } catch (Throwable $e) {}

        // Sistema nuevo: opciones por bloque de invitados.
        try {
            $stmtConteoInvitados = $pdo->prepare("
                SELECT rio.opcion_comida_id, COUNT(*) AS total
                FROM reserva_invitado_opciones rio
                INNER JOIN reserva_invitados ri ON ri.id = rio.reserva_invitado_id
                INNER JOIN reservas r ON r.id = ri.reserva_id
                WHERE r.acto_id = :acto_id AND r.estado = 'confirmada'
                GROUP BY rio.opcion_comida_id
            ");
            $stmtConteoInvitados->execute(['acto_id' => $acto_id]);
            foreach ($stmtConteoInvitados->fetchAll() as $row) {
                $opcionId = (int)($row['opcion_comida_id'] ?? 0);
                $categoria = $opcionCategoria[$opcionId] ?? null;
                if ($categoria !== null && isset($totalesPorBloque[$categoria][$opcionId])) {
                    $totalesPorBloque[$categoria][$opcionId]['total'] += (int)$row['total'];
                }
            }
        } catch (Throwable $e) {}

        // Fallback para reservas antiguas que solo guardaban una opcion_comida_id en reservas.
        try {
            $stmtConteoAntiguoReservas = $pdo->prepare("
                SELECT r.opcion_comida_id, COUNT(*) AS total
                FROM reservas r
                LEFT JOIN reserva_opciones ro ON ro.reserva_id = r.id
                WHERE r.acto_id = :acto_id
                  AND r.estado = 'confirmada'
                  AND r.opcion_comida_id IS NOT NULL
                  AND ro.id IS NULL
                GROUP BY r.opcion_comida_id
            ");
            $stmtConteoAntiguoReservas->execute(['acto_id' => $acto_id]);
            foreach ($stmtConteoAntiguoReservas->fetchAll() as $row) {
                $opcionId = (int)($row['opcion_comida_id'] ?? 0);
                $categoria = $opcionCategoria[$opcionId] ?? null;
                if ($categoria !== null && isset($totalesPorBloque[$categoria][$opcionId])) {
                    $totalesPorBloque[$categoria][$opcionId]['total'] += (int)$row['total'];
                }
            }
        } catch (Throwable $e) {}

        // Fallback para invitados antiguos que solo guardaban una opcion_comida_id.
        try {
            $stmtConteoAntiguoInvitados = $pdo->prepare("
                SELECT ri.opcion_comida_id, COUNT(*) AS total
                FROM reserva_invitados ri
                INNER JOIN reservas r ON r.id = ri.reserva_id
                LEFT JOIN reserva_invitado_opciones rio ON rio.reserva_invitado_id = ri.id
                WHERE r.acto_id = :acto_id
                  AND r.estado = 'confirmada'
                  AND ri.opcion_comida_id IS NOT NULL
                  AND rio.id IS NULL
                GROUP BY ri.opcion_comida_id
            ");
            $stmtConteoAntiguoInvitados->execute(['acto_id' => $acto_id]);
            foreach ($stmtConteoAntiguoInvitados->fetchAll() as $row) {
                $opcionId = (int)($row['opcion_comida_id'] ?? 0);
                $categoria = $opcionCategoria[$opcionId] ?? null;
                if ($categoria !== null && isset($totalesPorBloque[$categoria][$opcionId])) {
                    $totalesPorBloque[$categoria][$opcionId]['total'] += (int)$row['total'];
                }
            }
        } catch (Throwable $e) {}
    } catch (Throwable $e) {
        $totalesPorBloque = [];
    }
}

include __DIR__ . '/header.php';
include __DIR__ . '/sidebar.php';
?>
<main class="main-dashboard">
<header class="dashboard-topbar"><button class="mobile-menu-btn" type="button" onclick="toggleSidebar()">☰</button><div><h1>Reservas</h1><p>Gestión de reservas, pagos y códigos QR</p></div><div class="topbar-actions"><a href="index.php" class="topbar-btn">← Panel</a><a href="logout.php" class="topbar-btn">➤ Salir</a></div></header>
<section class="dashboard-content">
<div class="card-modern mb-4 admin-reservas-filter-card">
<form class="row g-2 align-items-end admin-reservas-filter-form">
<div class="col-md-5"><label class="form-label">Filtrar por acto</label><select class="form-select" name="acto_id"><option value="0">Todos los actos</option><?php foreach ($actos as $acto): ?><option value="<?= $acto['id'] ?>" <?= $acto_id === (int)$acto['id'] ? 'selected' : '' ?>><?= e($acto['titulo']) ?></option><?php endforeach; ?></select></div>
<div class="col-md-4"><label class="form-label">Buscar fallero/a</label><input class="form-control admin-reservas-search-input" type="search" name="q" value="<?= e($busquedaFallero) ?>" placeholder="Nombre o apellidos del fallero/a"></div>
<div class="col-auto"><button class="btn btn-primary">Buscar</button></div><div class="col-auto"><a class="btn btn-light" href="reservas.php">Limpiar</a></div>
<?php if ($acto_id): ?><div class="col-auto"><a class="btn btn-success" href="exportar_reservas.php?acto_id=<?= (int)$acto_id ?>">Descargar reservas</a></div><?php endif; ?>
</form>
<?php if ($busquedaFallero !== ''): ?><div class="admin-search-result-note">Mostrando reservas que coinciden con: <strong><?= e($busquedaFallero) ?></strong></div><?php endif; ?>
</div>
<?php if ($totalesPorBloque): ?>
<div class="reservas-bloques-resumen mb-4">
    <?php foreach ($totalesPorBloque as $bloque => $opcionesBloque): ?>
        <section class="card-modern reservas-bloque-card">
            <div class="reservas-bloque-head">
                <div class="stat-icon">🍽️</div>
                <h2><?= e($bloque) ?></h2>
            </div>
            <div class="reservas-bloque-opciones">
                <?php foreach ($opcionesBloque as $opcionBloque): ?>
                    <div class="reservas-bloque-opcion">
                        <span><?= e($opcionBloque['nombre']) ?></span>
                        <strong><?= e((string)$opcionBloque['total']) ?></strong>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<form method="post" id="bulkPagoForm" class="admin-bulk-pay-form">
<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<input type="hidden" name="action" value="bulk_pago">
<div class="admin-bulk-toolbar card-modern">
    <label class="admin-check-label admin-check-all">
        <input class="admin-reserva-check" type="checkbox" id="checkTodasReservas">
        <span>Seleccionar todas</span>
    </label>
    <button class="admin-btn admin-btn-pay admin-bulk-pay-btn" type="submit">Marcar seleccionadas como pagadas</button>
</div>
</form>
<div class="admin-reservas-grid">
<?php foreach ($lineas as $reserva): ?>
<?php
$estadoClase = match ($reserva['estado'] ?? '') {
    'confirmada' => 'status-confirmada',
    'cancelada' => 'status-cancelada',
    'pendiente' => 'status-pendiente',
    default => 'status-pendiente',
};
$esInvitado = (($reserva['_tipo_linea'] ?? '') === 'invitado');
?>
<article class="admin-reserva-card <?= $esInvitado ? 'admin-reserva-card-invitado' : '' ?>">
    <div class="admin-reserva-card-head">
        <div>
            <span class="admin-card-label">Persona</span>
            <h3><?= e($reserva['_nombre_linea']) ?></h3>
            <?php if ($esInvitado): ?><span class="admin-pill admin-pill-info">Invitado/a</span><?php endif; ?>
        </div>
        <div class="admin-card-check-wrap">
            <?php if (!$esInvitado && !(int)$reserva['pagada']): ?>
                <label class="admin-check-card" title="Seleccionar reserva">
                    <input class="admin-reserva-check js-reserva-bulk-check" type="checkbox" name="reserva_ids[]" value="<?= (int)$reserva['id'] ?>" form="bulkPagoForm">
                    <span></span>
                </label>
            <?php else: ?>
                <span class="admin-pill <?= (int)$reserva['pagada'] ? 'status-pagada' : 'admin-pill-muted' ?>"><?= (int)$reserva['pagada'] ? 'Pagada' : 'No seleccionable' ?></span>
            <?php endif; ?>
        </div>
    </div>

    <div class="reserva-card-fields">
        <div class="reserva-card-row"><span>Acto</span><strong><?= e($reserva['acto']) ?></strong></div>
        <div class="reserva-card-row reserva-card-row-block"><span>Opciones</span><strong>
            <?php foreach (($reserva['_opcion_lineas'] ?? []) as $lineaOpcion): ?>
                <span class="usuario-invitado-linea"><?= e($lineaOpcion) ?></span>
            <?php endforeach; ?>
            <?php if (empty($reserva['_opcion_lineas'])): ?>- <?php endif; ?>
        </strong></div>
        <div class="reserva-card-row"><span>Invitado de</span><strong><?= e($reserva['_invitado_de']) ?></strong></div>
        <div class="reserva-card-row"><span>Estado</span><strong><span class="admin-pill <?= e($estadoClase) ?>"><?= e(ucfirst((string)$reserva['estado'])) ?></span></strong></div>
        <div class="reserva-card-row"><span>Pago</span><strong><?= (int)$reserva['pagada'] ? '<span class="admin-pill status-pagada">Pagada</span>' : '<span class="admin-pill status-no-pagada">No pagada</span>' ?></strong></div>
        <div class="reserva-card-row"><span>QR</span><strong><?= (int)$reserva['qr_usado'] ? '<span class="admin-pill status-usado">Usado</span>' : ((int)$reserva['pagada'] ? '<span class="admin-pill status-disponible">Disponible</span>' : '<span class="text-muted">-</span>') ?></strong></div>
    </div>

    <div class="admin-card-actions">
    <?php if (!$esInvitado): ?>
        <form method="post" class="admin-action-form admin-status-form">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="action" value="estado">
            <input type="hidden" name="id" value="<?= (int)$reserva['id'] ?>">
            <select class="admin-status-select <?= e($estadoClase) ?>" name="estado" aria-label="Cambiar estado">
                <?php foreach (['confirmada','cancelada','pendiente'] as $estado): ?>
                    <option value="<?= $estado ?>" <?= $reserva['estado'] === $estado ? 'selected' : '' ?>><?= ucfirst($estado) ?></option>
                <?php endforeach; ?>
            </select>
            <button class="admin-btn admin-btn-save">Guardar</button>
        </form>
        <form method="post" class="admin-action-form admin-pay-form">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="id" value="<?= (int)$reserva['id'] ?>">
            <input type="hidden" name="action" value="<?= (int)$reserva['pagada'] ? 'no_pago' : 'pago' ?>">
            <button class="admin-btn <?= (int)$reserva['pagada'] ? 'admin-btn-unpay' : 'admin-btn-pay' ?>"><?= (int)$reserva['pagada'] ? 'Quitar pago' : 'Marcar pagada' ?></button>
        </form>
    <?php else: ?>
        <span class="admin-pill admin-pill-muted">Incluido en la reserva</span>
    <?php endif; ?>
    </div>
</article>
<?php endforeach; ?>
<?php if (!$lineas): ?><div class="card-modern text-muted">No hay reservas.</div><?php endif; ?>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkAll = document.getElementById('checkTodasReservas');
    const checks = Array.from(document.querySelectorAll('.js-reserva-bulk-check'));
    const form = document.getElementById('bulkPagoForm');

    function syncCheckAll() {
        if (!checkAll) return;
        const selected = checks.filter((check) => check.checked).length;
        checkAll.checked = checks.length > 0 && selected === checks.length;
        checkAll.indeterminate = selected > 0 && selected < checks.length;
    }

    if (checkAll) {
        checkAll.addEventListener('change', function () {
            checks.forEach((check) => { check.checked = checkAll.checked; });
            syncCheckAll();
        });
    }

    checks.forEach((check) => check.addEventListener('change', syncCheckAll));

    if (form) {
        form.addEventListener('submit', function (event) {
            if (!checks.some((check) => check.checked)) {
                event.preventDefault();
                alert('Selecciona al menos una reserva para marcarla como pagada.');
            }
        });
    }
});
</script>
</section></main><?php include __DIR__ . '/footer.php'; ?>
