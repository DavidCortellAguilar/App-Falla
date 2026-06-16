<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/comidas_helpers.php';
ensure_comidas_multiples_schema($pdo);
require_admin();

$token = trim((string)($_POST['t'] ?? $_GET['t'] ?? ''));
$accion = trim((string)($_POST['accion'] ?? ''));
$categoriaPost = trim((string)($_POST['categoria'] ?? ''));
$resultado = 'invalido';
$mensaje = 'El código no existe, no está pagado o la reserva no está confirmada.';
$reserva = null;
$opcionesReservaTexto = 'Sin opción';
$invitadosTexto = 'Sin invitado';
$bloques = [];
$justValidated = false;

function cargar_reserva_qr_pagina(PDO $pdo, string $token): ?array
{
    $stmt = $pdo->prepare("SELECT r.*, a.titulo AS acto, a.fecha, a.hora, CONCAT(f.nombre,' ',f.apellidos) AS fallero, oc.nombre AS opcion, oci.nombre AS invitado_opcion FROM reservas r JOIN actos a ON a.id=r.acto_id JOIN falleros f ON f.id=r.fallero_id LEFT JOIN opciones_comida oc ON oc.id=r.opcion_comida_id LEFT JOIN opciones_comida oci ON oci.id=r.invitado_opcion_comida_id WHERE r.qr_token=:token LIMIT 1");
    $stmt->execute(['token'=>$token]);
    $row = $stmt->fetch();
    return $row ?: null;
}

if ($token !== '') {
    try {
        $pdo->beginTransaction();
        $reserva = cargar_reserva_qr_pagina($pdo, $token);
        if ($reserva && (int)$reserva['pagada'] && $reserva['estado'] === 'confirmada') {
            if ($accion === 'validar_bloque') {
                $validacion = validar_bloque_qr($pdo, $reserva, $categoriaPost, isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null);
                $mensaje = $validacion['mensaje'];
                $justValidated = (bool)$validacion['ok'];
                $reserva = cargar_reserva_qr_pagina($pdo, $token) ?: $reserva;
            }

            $bloques = qr_bloques_reserva($pdo, $reserva);
            if ($accion === '' && count($bloques) === 1 && qr_bloques_pendientes($bloques) === 1) {
                $validacion = validar_bloque_qr($pdo, $reserva, (string)$bloques[0]['categoria'], isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null);
                $mensaje = $validacion['mensaje'];
                $justValidated = true;
                $reserva = cargar_reserva_qr_pagina($pdo, $token) ?: $reserva;
                $bloques = qr_bloques_reserva($pdo, $reserva);
            } elseif (!$bloques && !(int)$reserva['qr_usado']) {
                $pdo->prepare("UPDATE reservas SET qr_usado=1, fecha_qr_usado=NOW(), validado_por=:user_id, updated_at=NOW() WHERE id=:id")->execute(['user_id'=>$_SESSION['user_id'] ?? null, 'id'=>$reserva['id']]);
                $justValidated = true;
                $reserva['qr_usado'] = 1;
                $mensaje = 'Reserva validada correctamente.';
            }

            $pendientes = qr_bloques_pendientes($bloques);
            if (count($bloques) > 1 && $pendientes > 0) {
                $resultado = 'parcial';
                if (!$justValidated) $mensaje = 'QR leído. Marca el bloque que quieres canjear.';
            } elseif ($justValidated) {
                $resultado = 'valido';
                if ($pendientes === 0 && count($bloques) > 1) $mensaje .= ' Todos los bloques de este QR quedan canjeados.';
            } elseif ((count($bloques) > 0 && $pendientes === 0) || (int)$reserva['qr_usado']) {
                $resultado = 'usado';
                $mensaje = count($bloques) > 1 ? 'Todos los bloques de este QR ya están canjeados.' : 'Esta reserva ya había sido escaneada anteriormente.';
            } else {
                $resultado = 'valido';
                $mensaje = 'Reserva validada correctamente.';
            }

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
            }
        }
        $pdo->commit();
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        $resultado = 'error';
        $mensaje = 'Error al validar el QR.';
    }
}

$page_title = 'Validar QR';
include __DIR__ . '/header.php'; include __DIR__ . '/sidebar.php';
?>
<main class="main-dashboard"><header class="dashboard-topbar"><button class="mobile-menu-btn" type="button" onclick="toggleSidebar()">☰</button><div><h1>Validación QR</h1><p>Control de acceso de reservas pagadas</p></div><div class="topbar-actions"><a href="escanear_qr.php" class="topbar-btn">📷 Escanear otro QR</a><a href="reservas.php" class="topbar-btn">← Reservas</a></div></header><section class="dashboard-content"><div class="card-modern qr-result <?= e($resultado) ?>">
<?php if ($resultado === 'valido'): ?><h2>✅ QR válido</h2><p><?= e($mensaje) ?></p>
<?php elseif ($resultado === 'parcial'): ?><h2>ℹ️ Elige qué canjear</h2><p><?= e($mensaje) ?></p>
<?php elseif ($resultado === 'usado'): ?><h2>❌ QR ya usado</h2><p><?= e($mensaje) ?></p>
<?php else: ?><h2>❌ QR no válido</h2><p><?= e($mensaje) ?></p><?php endif; ?>
<?php if ($reserva): ?><div class="detail-list mt-4"><div><span>Acto</span><strong><?= e($reserva['acto']) ?></strong></div><div><span>Fecha</span><strong><?= e($reserva['fecha'].' '.substr((string)$reserva['hora'],0,5)) ?></strong></div><div><span>Fallero/a</span><strong><?= e($reserva['fallero']) ?></strong></div><div><span>Opciones</span><strong><?= nl2br(e($opcionesReservaTexto)) ?></strong></div><div><span>Invitado/s</span><strong><?= nl2br(e($invitadosTexto)) ?></strong></div></div><?php endif; ?>
<?php if ($reserva && count($bloques) > 1): ?>
    <div class="qr-bloques-validacion mt-4">
        <?php foreach ($bloques as $bloque): ?>
            <form method="post" class="mb-2">
                <input type="hidden" name="t" value="<?= e($token) ?>">
                <input type="hidden" name="accion" value="validar_bloque">
                <input type="hidden" name="categoria" value="<?= e($bloque['categoria']) ?>">
                <button class="btn <?= !empty($bloque['validado']) ? 'btn-danger' : 'btn-success' ?> w-100" type="submit" <?= !empty($bloque['validado']) ? 'disabled' : '' ?>>
                    <?= e($bloque['categoria'] . ': ' . $bloque['opcion']) ?> — <?= !empty($bloque['validado']) ? 'Canjeado' : 'Canjear' ?>
                </button>
            </form>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<div class="text-center mt-4"><a href="escanear_qr.php" class="btn btn-primary">📷 Escanear otro QR</a></div></div></section></main>
<style>.qr-result.parcial{border:4px solid #3b82f6;background:#eff6ff}.qr-bloques-validacion .btn{font-weight:900;padding:14px;border-radius:14px}</style>
<?php include __DIR__ . '/footer.php'; ?>
