<?php
require_once __DIR__ . '/config.php';
require_admin();
header('Content-Type: application/json; charset=utf-8');

$token = trim($_GET['t'] ?? '');
$resultado = 'invalido';
$reserva = null;

try {
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
            $pdo->prepare("UPDATE reservas SET qr_usado=1, fecha_qr_usado=NOW(), validado_por=:user_id, updated_at=NOW() WHERE id=:id")
                ->execute(['user_id'=>$_SESSION['user_id'] ?? null, 'id'=>$reserva['id']]);
            $pdo->commit();
        }
    }

    echo json_encode([
        'ok' => true,
        'resultado' => $resultado,
        'mensaje' => $resultado === 'valido' ? 'Reserva validada correctamente. Este QR queda marcado como usado.' : ($resultado === 'usado' ? 'Esta reserva ya había sido escaneada anteriormente.' : 'El código no existe, no está pagado o la reserva no está confirmada.'),
        'reserva' => $reserva ? [
            'acto' => $reserva['acto'],
            'fecha' => trim($reserva['fecha'].' '.substr((string)$reserva['hora'],0,5)),
            'fallero' => $reserva['fallero'],
            'opcion' => $reserva['opcion'] ?: 'Sin opción',
            'invitado' => $invitadosTexto,
        ] : null,
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(['ok'=>false, 'resultado'=>'error', 'mensaje'=>'Error al validar el QR.'], JSON_UNESCAPED_UNICODE);
}
