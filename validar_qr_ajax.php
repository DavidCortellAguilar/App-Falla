<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/comidas_helpers.php';
ensure_comidas_multiples_schema($pdo);
require_admin();
header('Content-Type: application/json; charset=utf-8');

function cargar_reserva_qr(PDO $pdo, string $token): ?array
{
    $stmt = $pdo->prepare("SELECT r.*, a.titulo AS acto, a.fecha, a.hora, CONCAT(f.nombre,' ',f.apellidos) AS fallero, oc.nombre AS opcion, oci.nombre AS invitado_opcion FROM reservas r JOIN actos a ON a.id=r.acto_id JOIN falleros f ON f.id=r.fallero_id LEFT JOIN opciones_comida oc ON oc.id=r.opcion_comida_id LEFT JOIN opciones_comida oci ON oci.id=r.invitado_opcion_comida_id WHERE r.qr_token=:token LIMIT 1");
    $stmt->execute(['token' => $token]);
    $reserva = $stmt->fetch();
    return $reserva ?: null;
}

function datos_respuesta_reserva(PDO $pdo, array $reserva): array
{
    $opcionesPorReserva = opciones_texto_por_reserva_ids($pdo, [(int)$reserva['id']]);
    $opcionesReservaTexto = opciones_texto_plano($opcionesPorReserva[(int)$reserva['id']] ?? [], $reserva['opcion'] ?? '');

    $stmtInv = $pdo->prepare("SELECT ri.id, ri.nombre, ri.tipo, oc.nombre AS opcion_nombre FROM reserva_invitados ri LEFT JOIN opciones_comida oc ON oc.id=ri.opcion_comida_id WHERE ri.reserva_id=:reserva_id ORDER BY ri.id ASC");
    $stmtInv->execute(['reserva_id' => $reserva['id']]);
    $invitados = $stmtInv->fetchAll();
    $opcionesPorInvitado = opciones_texto_por_invitado_ids($pdo, array_map(static fn($inv) => (int)$inv['id'], $invitados));
    $partesInv = [];
    foreach ($invitados as $inv) {
        $opcionesInv = opciones_texto_plano($opcionesPorInvitado[(int)$inv['id']] ?? [], $inv['opcion_nombre'] ?? '');
        $partesInv[] = trim($inv['nombre'].' · '.$inv['tipo']."\n".$opcionesInv);
    }
    if ($partesInv) {
        $invitadosTexto = implode("\n\n", $partesInv);
    } elseif (!empty($reserva['invitado_nombre'])) {
        $legacyInv = trim(($reserva['invitado_nombre'] ?: 'Sin invitado') . ($reserva['invitado_tipo'] ? ' · '.$reserva['invitado_tipo'] : ''));
        $legacyOpc = !empty($reserva['invitado_opcion']) ? "\nComida: ".$reserva['invitado_opcion'] : '';
        $invitadosTexto = $legacyInv . $legacyOpc;
    } else {
        $invitadosTexto = 'Sin invitado';
    }

    $bloques = qr_bloques_reserva($pdo, $reserva);
    return [
        'acto' => $reserva['acto'],
        'fecha' => trim($reserva['fecha'].' '.substr((string)$reserva['hora'],0,5)),
        'fallero' => $reserva['fallero'],
        'opcion' => $opcionesReservaTexto,
        'invitado' => $invitadosTexto,
        'bloques' => $bloques,
        'pendientes' => qr_bloques_pendientes($bloques),
        'total_bloques' => count($bloques),
    ];
}

try {
    $token = trim((string)($_POST['t'] ?? $_GET['t'] ?? ''));
    $accion = trim((string)($_POST['accion'] ?? $_GET['accion'] ?? ''));
    $categoria = trim((string)($_POST['categoria'] ?? $_GET['categoria'] ?? ''));

    if ($token === '') {
        echo json_encode(['ok'=>true, 'resultado'=>'invalido', 'mensaje'=>'El código no existe, no está pagado o la reserva no está confirmada.', 'reserva'=>null], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $pdo->beginTransaction();
    $reserva = cargar_reserva_qr($pdo, $token);
    if (!$reserva || !(int)$reserva['pagada'] || $reserva['estado'] !== 'confirmada') {
        $pdo->rollBack();
        echo json_encode(['ok'=>true, 'resultado'=>'invalido', 'mensaje'=>'El código no existe, no está pagado o la reserva no está confirmada.', 'reserva'=>null], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($accion === 'validar_bloque') {
        $validacion = validar_bloque_qr($pdo, $reserva, $categoria, isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null);
        $reserva = cargar_reserva_qr($pdo, $token) ?: $reserva;
        $datos = datos_respuesta_reserva($pdo, $reserva);
        $pendientes = (int)$datos['pendientes'];
        $pdo->commit();
        echo json_encode([
            'ok' => true,
            'resultado' => $validacion['ok'] ? ($pendientes === 0 ? 'valido' : 'parcial') : 'usado',
            'mensaje' => $validacion['mensaje'] . ($validacion['ok'] && $pendientes === 0 ? ' Todos los bloques de este QR quedan canjeados.' : ''),
            'reserva' => $datos,
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $bloques = qr_bloques_reserva($pdo, $reserva);
    $justValidated = false;
    if (count($bloques) === 1 && qr_bloques_pendientes($bloques) === 1) {
        validar_bloque_qr($pdo, $reserva, (string)$bloques[0]['categoria'], isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null);
        $justValidated = true;
        $reserva = cargar_reserva_qr($pdo, $token) ?: $reserva;
    } elseif (!$bloques && !(int)$reserva['qr_usado']) {
        $justValidated = true;
        $pdo->prepare("UPDATE reservas SET qr_usado=1, fecha_qr_usado=NOW(), validado_por=:user_id, updated_at=NOW() WHERE id=:id")
            ->execute(['user_id'=>$_SESSION['user_id'] ?? null, 'id'=>$reserva['id']]);
        $reserva['qr_usado'] = 1;
    }

    $datos = datos_respuesta_reserva($pdo, $reserva);
    $pendientes = (int)$datos['pendientes'];
    if ($datos['total_bloques'] > 1 && $pendientes > 0) {
        $resultado = 'parcial';
        $mensaje = 'QR leído. Marca el bloque que quieres canjear.';
    } elseif ($pendientes === 0 || (int)$reserva['qr_usado']) {
        $resultado = 'valido';
        $mensaje = 'Reserva validada correctamente.';
        if ($datos['total_bloques'] > 1) $mensaje = 'Todos los bloques de este QR ya están canjeados.';
    } else {
        $resultado = 'usado';
        $mensaje = 'Esta reserva ya había sido escaneada anteriormente.';
    }
    if ($datos['total_bloques'] > 0 && $pendientes === 0 && !$justValidated) {
        $resultado = 'usado';
        $mensaje = 'Todos los bloques de este QR ya están canjeados.';
    }

    $pdo->commit();
    echo json_encode(['ok'=>true, 'resultado'=>$resultado, 'mensaje'=>$mensaje, 'reserva'=>$datos], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['ok'=>false, 'resultado'=>'error', 'mensaje'=>'Error al validar el QR.'], JSON_UNESCAPED_UNICODE);
}
