<?php
require_once __DIR__ . '/config.php';
require_login();
$page_title = 'Mis reservas';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();

    $pdo->prepare("UPDATE reservas r JOIN actos a ON a.id=r.acto_id SET r.estado='cancelada', r.updated_at=NOW() WHERE r.id=:id AND r.fallero_id=:fallero_id AND a.estado='abierto'")
        ->execute(['id' => (int) $_POST['id'], 'fallero_id' => (int) ($_SESSION['fallero_id'] ?? 0)]);
    log_activity($pdo, 'cancel', 'reservas', 'Reserva cancelada por fallero');
    redirect('mis_reservas.php');
}

$stmt = $pdo->prepare("
    SELECT r.*, a.titulo, a.fecha, a.hora, a.ubicacion, a.estado AS acto_estado, oc.nombre AS opcion, oci.nombre AS invitado_opcion
    FROM reservas r
    JOIN actos a ON a.id = r.acto_id
    LEFT JOIN opciones_comida oc ON oc.id = r.opcion_comida_id
    LEFT JOIN opciones_comida oci ON oci.id = r.invitado_opcion_comida_id
    WHERE r.fallero_id = :fallero_id
    ORDER BY a.fecha DESC, a.hora DESC
");
$stmt->execute(['fallero_id' => (int) ($_SESSION['fallero_id'] ?? 0)]);
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

include __DIR__ . '/header.php';
include __DIR__ . '/sidebar.php';
?>

<main class="main-dashboard">
    <header class="dashboard-topbar">
        <button class="mobile-menu-btn" type="button" onclick="toggleSidebar()">☰</button>
        <div>
            <h1>Mis reservas</h1>
            <p>Historial de reservas</p>
        </div>
        <div class="topbar-actions">
            <a href="index.php" class="topbar-btn">← Panel</a>
            <a href="logout.php" class="topbar-btn">➤ Salir</a>
        </div>
    </header>

    <section class="dashboard-content">

<div class="card-modern table-card">
    <table class="table align-middle">
        <thead><tr><th>Acto</th><th>Fecha</th><th>Opción</th><th>Invitado</th><th>Estado</th><th>QR</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($reservas as $r): ?>
            <tr>
                <td><?= e($r['titulo']) ?></td>
                <td><?= e($r['fecha']) ?> <?= e(substr((string)$r['hora'],0,5)) ?></td>
                <td><?= e($r['opcion']) ?></td>
                <td>
                    <?php $invs = $invitadosPorReserva[(int)$r['id']] ?? []; ?>
                    <?php if ($invs): ?>
                        <?php foreach ($invs as $inv): ?>
                            <div><?= e($inv['nombre']) ?> · <?= e($inv['tipo']) ?><?= $inv['opcion_nombre'] ? '<br><small>Menú: ' . e($inv['opcion_nombre']) . '</small>' : '' ?></div>
                        <?php endforeach; ?>
                    <?php elseif (!empty($r['invitado_nombre'])): ?>
                        <?= e($r['invitado_nombre']) ?><?= $r['invitado_tipo'] ? ' · ' . e($r['invitado_tipo']) : '' ?><?= $r['invitado_opcion'] ? '<br><small>Menú: ' . e($r['invitado_opcion']) . '</small>' : '' ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <td><?= e($r['estado']) ?><?= strtolower((string)$r['acto_estado']) !== 'abierto' ? ' · acto cerrado' : '' ?></td>
                <td><?php if ((int)$r['pagada'] && $r['qr_token']): ?><?php $qrUrl = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/validar_qr.php?t=' . urlencode($r['qr_token']); $qrImg = 'https://api.qrserver.com/v1/create-qr-code/?size=420x420&data=' . urlencode($qrUrl); ?><button type="button" class="btn btn-sm btn-primary" onclick="abrirQR('<?= e($qrImg) ?>')">Ver QR</button><br><small><?= (int)$r['qr_usado'] ? 'Usado' : 'Un solo uso' ?></small><?php else: ?><span class="text-muted">Pendiente de pago</span><?php endif; ?></td>
                <td class="text-end">
                    <?php if ($r['estado'] === 'confirmada' && strtolower((string)$r['acto_estado']) === 'abierto'): ?>
                        <form method="post" onsubmit="return confirm('¿Cancelar reserva?')">
                            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                            <input type="hidden" name="id" value="<?= $r['id'] ?>">
                            <button class="btn btn-sm btn-outline-danger">Cancelar</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
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
