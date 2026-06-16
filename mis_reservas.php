<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/comidas_helpers.php';
ensure_comidas_multiples_schema($pdo);
require_login();
$page_title = 'Mis reservas';

$falleroIdSesion = (int) ($_SESSION['fallero_id'] ?? 0);

$stmt = $pdo->prepare("SELECT id, familia_id FROM falleros WHERE id=:id LIMIT 1");
$stmt->execute(['id' => $falleroIdSesion]);
$falleroActual = $stmt->fetch();
$familiaId = (int) ($falleroActual['familia_id'] ?? 0);
$esRepresentante = false;
$idsPermitidos = [$falleroIdSesion];

if ($familiaId > 0) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM familia_representantes WHERE familia_id=:familia_id AND fallero_id=:fallero_id");
    $stmt->execute(['familia_id' => $familiaId, 'fallero_id' => $falleroIdSesion]);
    $esRepresentante = (int) $stmt->fetchColumn() > 0;

    if (!$esRepresentante) {
        $stmt = $pdo->prepare("SELECT representante_fallero_id FROM familias WHERE id=:id LIMIT 1");
        $stmt->execute(['id' => $familiaId]);
        $esRepresentante = ((int) $stmt->fetchColumn() === $falleroIdSesion);
    }

    if ($esRepresentante) {
        $stmt = $pdo->prepare("SELECT id FROM falleros WHERE familia_id=:familia_id AND estado='activo'");
        $stmt->execute(['familia_id' => $familiaId]);
        $idsPermitidos = array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
        if (!$idsPermitidos) {
            $idsPermitidos = [$falleroIdSesion];
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();

    $reservaId = (int) ($_POST['id'] ?? 0);
    if ($reservaId > 0 && $idsPermitidos) {
        $ph = implode(',', array_fill(0, count($idsPermitidos), '?'));
        // Si el acto sigue abierto, el fallero puede cancelar su reserva.
        // La cancelación elimina la reserva para que no aparezca como "cancelada"
        // ni en Mis reservas ni en Todas las reservas de administración.
        $pdo->beginTransaction();

        $stmtCheck = $pdo->prepare("
            SELECT r.id
            FROM reservas r
            JOIN actos a ON a.id=r.acto_id
            WHERE r.id=?
              AND r.fallero_id IN ($ph)
              AND r.estado='confirmada'
              AND a.estado='abierto'
            LIMIT 1
        ");
        $stmtCheck->execute(array_merge([$reservaId], $idsPermitidos));
        $puedeEliminar = (int) $stmtCheck->fetchColumn() > 0;

        if ($puedeEliminar) {
            $stmtInv = $pdo->prepare("DELETE FROM reserva_invitados WHERE reserva_id=?");
            $stmtInv->execute([$reservaId]);

            $stmtDel = $pdo->prepare("DELETE FROM reservas WHERE id=?");
            $stmtDel->execute([$reservaId]);

            log_activity($pdo, 'cancel', 'reservas', $esRepresentante ? 'Reserva familiar eliminada por representante' : 'Reserva eliminada por fallero');
        }

        $pdo->commit();
    }

    redirect('mis_reservas.php');
}

$ph = implode(',', array_fill(0, count($idsPermitidos), '?'));
$stmt = $pdo->prepare("
    SELECT r.*, a.titulo, a.fecha, a.hora, a.ubicacion, a.estado AS acto_estado,
           oc.nombre AS opcion, oci.nombre AS invitado_opcion,
           CONCAT(f.nombre, ' ', f.apellidos) AS fallero_nombre
    FROM reservas r
    JOIN actos a ON a.id = r.acto_id
    JOIN falleros f ON f.id = r.fallero_id
    LEFT JOIN opciones_comida oc ON oc.id = r.opcion_comida_id
    LEFT JOIN opciones_comida oci ON oci.id = r.invitado_opcion_comida_id
    WHERE r.fallero_id IN ($ph)
      AND r.estado='confirmada'
    ORDER BY a.fecha DESC, a.hora DESC, f.apellidos, f.nombre
");
$stmt->execute($idsPermitidos);
$reservas = $stmt->fetchAll();

$invitadosPorReserva = [];
$reservaIds = array_map(static fn($r) => (int)$r['id'], $reservas);
$opcionesPorReserva = opciones_texto_por_reserva_ids($pdo, $reservaIds);
$invitadoIds = [];
if ($reservaIds) {
    $phInv = implode(',', array_fill(0, count($reservaIds), '?'));
    $stmtInv = $pdo->prepare("SELECT ri.*, oc.nombre AS opcion_nombre FROM reserva_invitados ri LEFT JOIN opciones_comida oc ON oc.id=ri.opcion_comida_id WHERE ri.reserva_id IN ($phInv) ORDER BY ri.id ASC");
    $stmtInv->execute($reservaIds);
    foreach ($stmtInv->fetchAll() as $inv) {
        $invitadosPorReserva[(int)$inv['reserva_id']][] = $inv;
        $invitadoIds[] = (int)$inv['id'];
    }
}
$opcionesPorInvitado = opciones_texto_por_invitado_ids($pdo, $invitadoIds);

include __DIR__ . '/header.php';
include __DIR__ . '/sidebar.php';
?>

<main class="main-dashboard">
    <header class="dashboard-topbar">
        <button class="mobile-menu-btn" type="button" onclick="toggleSidebar()">☰</button>
        <div>
            <h1><?= $esRepresentante ? 'Reservas de mi familia' : 'Mis reservas' ?></h1>
            <p><?= $esRepresentante ? 'Como representante puedes cancelar reservas individuales de tu familia mientras el acto siga abierto.' : 'Reservas confirmadas' ?></p>
        </div>
        <div class="topbar-actions">
            <a href="mis_actos.php" class="topbar-btn">Actos disponibles</a>
            <a href="index.php" class="topbar-btn">← Panel</a>
            <a href="logout.php" class="topbar-btn">➤ Salir</a>
        </div>
    </header>

    <section class="dashboard-content">

<div class="mis-reservas-grid">
<?php foreach ($reservas as $r): ?>
<?php
$estadoClase = match ($r['estado'] ?? '') {
    'confirmada' => 'status-confirmada',
    'cancelada' => 'status-cancelada',
    'pendiente' => 'status-pendiente',
    default => 'status-pendiente',
};
$invs = $invitadosPorReserva[(int)$r['id']] ?? [];
$actoCerrado = strtolower((string)$r['acto_estado']) !== 'abierto';
?>
<article class="usuario-reserva-card">
    <div class="admin-reserva-card-head">
        <div>
            <span class="admin-card-label">Acto</span>
            <h3><?= e($r['titulo']) ?></h3>
            <?php if ($esRepresentante): ?><p class="usuario-reserva-persona"><?= e($r['fallero_nombre']) ?></p><?php endif; ?>
        </div>
        <span class="admin-pill <?= e($estadoClase) ?>"><?= e(ucfirst((string)$r['estado'])) ?></span>
    </div>

    <div class="reserva-card-fields">
        <?php if ($esRepresentante): ?><div class="reserva-card-row"><span>Fallero/a</span><strong><?= e($r['fallero_nombre']) ?></strong></div><?php endif; ?>
        <div class="reserva-card-row"><span>Fecha</span><strong><?= e($r['fecha']) ?> <?= e(substr((string)$r['hora'],0,5)) ?></strong></div>
        <div class="reserva-card-row"><span>Ubicación</span><strong><?= e($r['ubicacion'] ?: '-') ?></strong></div>
        <?php $lineasOpcionReserva = opciones_texto_fallback($opcionesPorReserva[(int)$r['id']] ?? [], $r['opcion'] ?? ''); ?>
        <div class="reserva-card-row reserva-card-row-block"><span>Opciones</span><strong>
            <?php foreach ($lineasOpcionReserva as $lineaOpcion): ?>
                <span class="usuario-invitado-linea"><?= e($lineaOpcion) ?></span>
            <?php endforeach; ?>
            <?php if (!$lineasOpcionReserva): ?>- <?php endif; ?>
        </strong></div>
        <div class="reserva-card-row reserva-card-row-block"><span>Invitados</span><strong>
            <?php if ($invs): ?>
                <?php foreach ($invs as $inv): ?>
                    <?php $lineasInvitado = opciones_texto_fallback($opcionesPorInvitado[(int)$inv['id']] ?? [], $inv['opcion_nombre'] ?? ''); ?>
                    <span class="usuario-invitado-linea">
                        <?= e($inv['nombre']) ?> · <?= e($inv['tipo']) ?>
                        <?php foreach ($lineasInvitado as $lineaInvitado): ?><small><?= e($lineaInvitado) ?></small><?php endforeach; ?>
                    </span>
                <?php endforeach; ?>
            <?php elseif (!empty($r['invitado_nombre'])): ?>
                <?= e($r['invitado_nombre']) ?><?= $r['invitado_tipo'] ? ' · ' . e($r['invitado_tipo']) : '' ?><?= $r['invitado_opcion'] ? '<small>Comida: ' . e($r['invitado_opcion']) . '</small>' : '' ?>
            <?php else: ?>
                -
            <?php endif; ?>
        </strong></div>
        <div class="reserva-card-row"><span>Pago</span><strong><?= (int)$r['pagada'] ? '<span class="admin-pill status-pagada">Pagada</span>' : '<span class="admin-pill status-no-pagada">Pendiente</span>' ?></strong></div>
        <?php if ($actoCerrado): ?><div class="reserva-card-row"><span>Acto</span><strong><span class="admin-pill admin-pill-muted">Cerrado</span></strong></div><?php endif; ?>
    </div>

    <div class="usuario-card-actions">
        <?php if ((int)$r['pagada'] && $r['qr_token']): ?>
            <?php $qrUrl = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/validar_qr.php?t=' . urlencode($r['qr_token']); $qrImg = 'https://api.qrserver.com/v1/create-qr-code/?size=420x420&data=' . urlencode($qrUrl); ?>
            <button type="button" class="admin-btn admin-btn-pay" onclick="abrirQR('<?= e($qrImg) ?>')">Ver QR</button>
            <small><?= (int)$r['qr_usado'] ? 'QR usado' : 'QR de un solo uso' ?></small>
        <?php else: ?>
            <span class="text-muted">QR pendiente de pago</span>
        <?php endif; ?>

        <?php if ($r['estado'] === 'confirmada' && !$actoCerrado): ?>
            <form method="post" onsubmit="return confirm('¿Cancelar esta reserva individual?')">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                <button class="admin-btn admin-btn-unpay">Cancelar reserva</button>
            </form>
        <?php endif; ?>
    </div>
</article>
<?php endforeach; ?>
<?php if (!$reservas): ?>
    <div class="card-modern text-muted">Todavía no hay reservas.</div>
<?php endif; ?>
</div>

</section>
</main>

<div id="qrModal" class="qr-modal" onclick="cerrarQR()">
    <div class="qr-modal-content" onclick="event.stopPropagation()">
        <button type="button" class="cerrar-qr" onclick="cerrarQR()">&times;</button>
        <img id="qrGrande" src="" alt="QR">
    </div>
</div>

<style>
.qr-modal{display:none;position:fixed;z-index:9999;inset:0;background:rgba(0,0,0,.75);justify-content:center;align-items:center;padding:20px}
.qr-modal-content{background:#fff;padding:24px;border-radius:22px;position:relative;overflow:visible;max-width:92vw;text-align:center}
.qr-modal-content img{width:380px;max-width:100%;height:auto}
.cerrar-qr{position:absolute;top:-18px;right:-18px;width:42px;height:42px;background:#fff;border:0;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:bold;color:#111827;line-height:1;cursor:pointer;box-shadow:0 4px 15px rgba(0,0,0,.25);z-index:20;transition:.2s}.cerrar-qr:hover{transform:scale(1.08);background:#f3f4f6}
</style>
<script>
function abrirQR(src){document.getElementById('qrGrande').src=src;document.getElementById('qrModal').style.display='flex';}
function cerrarQR(){document.getElementById('qrModal').style.display='none';document.getElementById('qrGrande').src='';}
</script>

<?php include __DIR__ . '/footer.php'; ?>
