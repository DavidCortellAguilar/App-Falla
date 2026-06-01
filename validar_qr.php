<?php
require_once __DIR__ . '/config.php';
require_admin();
$token = trim($_GET['t'] ?? '');
$resultado = 'invalido';
$reserva = null;
if ($token !== '') {
    $pdo->beginTransaction();
    $stmt = $pdo->prepare("SELECT r.*, a.titulo AS acto, a.fecha, a.hora, CONCAT(f.nombre,' ',f.apellidos) AS fallero, oc.nombre AS opcion, oci.nombre AS invitado_opcion FROM reservas r JOIN actos a ON a.id=r.acto_id JOIN falleros f ON f.id=r.fallero_id LEFT JOIN opciones_comida oc ON oc.id=r.opcion_comida_id LEFT JOIN opciones_comida oci ON oci.id=r.invitado_opcion_comida_id WHERE r.qr_token=:token FOR UPDATE");
    $stmt->execute(['token'=>$token]);
    $reserva = $stmt->fetch();
    $invitadosTexto = 'Sin invitado';
    if ($reserva) {
        $stmtInv = $pdo->prepare("SELECT ri.nombre, ri.tipo, oc.nombre AS opcion_nombre FROM reserva_invitados ri LEFT JOIN opciones_comida oc ON oc.id=ri.opcion_comida_id WHERE ri.reserva_id=:reserva_id ORDER BY ri.id ASC");
        $stmtInv->execute(['reserva_id' => $reserva['id']]);
        $partesInv = [];
        foreach ($stmtInv->fetchAll() as $inv) {
            $partesInv[] = trim($inv['nombre'].' · '.$inv['tipo'].($inv['opcion_nombre'] ? ' · Menú: '.$inv['opcion_nombre'] : ''));
        }
        if ($partesInv) {
            $invitadosTexto = implode(' | ', $partesInv);
        } elseif (!empty($reserva['invitado_nombre'])) {
            $invitadosTexto = trim(($reserva['invitado_nombre'] ?: 'Sin invitado') . ($reserva['invitado_tipo'] ? ' · '.$reserva['invitado_tipo'] : '') . (!empty($reserva['invitado_opcion']) ? ' · Menú: '.$reserva['invitado_opcion'] : ''));
        }
    }
    if (!$reserva || !(int)$reserva['pagada'] || $reserva['estado'] !== 'confirmada') {
        $resultado = 'invalido';
        $pdo->rollBack();
    } elseif ((int)$reserva['qr_usado']) {
        $resultado = 'usado';
        $pdo->commit();
    } else {
        $resultado = 'valido';
        $pdo->prepare("UPDATE reservas SET qr_usado=1, fecha_qr_usado=NOW(), validado_por=:user_id, updated_at=NOW() WHERE id=:id")->execute(['user_id'=>$_SESSION['user_id'] ?? null, 'id'=>$reserva['id']]);
        $pdo->commit();
    }
}
$page_title = 'Validar QR';
include __DIR__ . '/header.php'; include __DIR__ . '/sidebar.php';
?>
<main class="main-dashboard"><header class="dashboard-topbar"><button class="mobile-menu-btn" type="button" onclick="toggleSidebar()">☰</button><div><h1>Validación QR</h1><p>Control de acceso de reservas pagadas</p></div><div class="topbar-actions"><a href="escanear_qr.php" class="topbar-btn">📷 Escanear otro QR</a><a href="reservas.php" class="topbar-btn">← Reservas</a></div></header><section class="dashboard-content"><div class="card-modern qr-result <?= e($resultado) ?>">
<?php if ($resultado === 'valido'): ?><h2>✅ QR válido</h2><p>Reserva validada correctamente. Este QR queda marcado como usado.</p>
<?php elseif ($resultado === 'usado'): ?><h2>❌ QR ya usado</h2><p>Esta reserva ya había sido escaneada anteriormente.</p>
<?php else: ?><h2>❌ QR no válido</h2><p>El código no existe, no está pagado o la reserva no está confirmada.</p><?php endif; ?>
<?php if ($reserva): ?><div class="detail-list mt-4"><div><span>Acto</span><strong><?= e($reserva['acto']) ?></strong></div><div><span>Fecha</span><strong><?= e($reserva['fecha'].' '.substr((string)$reserva['hora'],0,5)) ?></strong></div><div><span>Fallero/a</span><strong><?= e($reserva['fallero']) ?></strong></div><div><span>Opción</span><strong><?= e($reserva['opcion'] ?: 'Sin opción') ?></strong></div><div><span>Invitado/s</span><strong><?= e($invitadosTexto) ?></strong></div></div><?php endif; ?>
<div class="text-center mt-4"><a href="escanear_qr.php" class="btn btn-primary">📷 Escanear otro QR</a></div></div></section></main><?php include __DIR__ . '/footer.php'; ?>
