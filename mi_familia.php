<?php
require_once __DIR__ . '/config.php';
require_login();

$page_title = 'Mi Familia';
$falleroIdSesion = (int) ($_SESSION['fallero_id'] ?? 0);

$stmt = $pdo->prepare("\n    SELECT f.*, fa.nombre AS familia_nombre, fa.observaciones, fa.representante_fallero_id,\n           CONCAT(fr.nombre, ' ', fr.apellidos) AS representante\n    FROM falleros f\n    LEFT JOIN familias fa ON fa.id = f.familia_id\n    LEFT JOIN falleros fr ON fr.id = fa.representante_fallero_id\n    WHERE f.id = :id\n    LIMIT 1\n");
$stmt->execute(['id' => $falleroIdSesion]);
$falleroActual = $stmt->fetch();

$familiaId = (int) ($falleroActual['familia_id'] ?? 0);
$miembros = [];
$reservasFamilia = [];
$representantesFamilia = [];
$esRepresentanteFamilia = false;

if ($familiaId > 0) {
    $stmt = $pdo->prepare("\n        SELECT id, nombre, apellidos, tipo, estado, dni, telefono, email\n        FROM falleros\n        WHERE familia_id = :familia_id\n        ORDER BY apellidos, nombre\n    ");
    $stmt->execute(['familia_id' => $familiaId]);
    $miembros = $stmt->fetchAll();

    $stmt = $pdo->prepare("\n        SELECT fr.fallero_id, CONCAT(f.nombre, ' ', f.apellidos) AS nombre_completo\n        FROM familia_representantes fr\n        INNER JOIN falleros f ON f.id = fr.fallero_id\n        WHERE fr.familia_id = :familia_id\n        ORDER BY fr.created_at ASC, fr.fallero_id ASC\n    ");
    $stmt->execute(['familia_id' => $familiaId]);
    $representantesFamilia = $stmt->fetchAll();

    foreach ($representantesFamilia as $rep) {
        if ((int) $rep['fallero_id'] === $falleroIdSesion) {
            $esRepresentanteFamilia = true;
            break;
        }
    }

    if (!$esRepresentanteFamilia && (int)($falleroActual['representante_fallero_id'] ?? 0) === $falleroIdSesion) {
        $esRepresentanteFamilia = true;
    }

    $stmt = $pdo->prepare("\n        SELECT r.*, a.titulo, a.fecha, a.hora, a.estado AS acto_estado,\n               oc.nombre AS opcion, CONCAT(f.nombre, ' ', f.apellidos) AS fallero\n        FROM reservas r\n        INNER JOIN actos a ON a.id = r.acto_id\n        INNER JOIN falleros f ON f.id = r.fallero_id\n        LEFT JOIN opciones_comida oc ON oc.id = r.opcion_comida_id\n        WHERE f.familia_id = :familia_id\n          AND r.estado = 'confirmada'\n        ORDER BY a.fecha DESC, a.hora DESC, f.apellidos, f.nombre\n        LIMIT 100\n    ");
    $stmt->execute(['familia_id' => $familiaId]);
    $reservasFamilia = $stmt->fetchAll();
}

$baseUrl = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');

include __DIR__ . '/header.php';
include __DIR__ . '/sidebar.php';
?>

<main class="main-dashboard">
    <header class="dashboard-topbar">
        <button class="mobile-menu-btn" type="button" onclick="toggleSidebar()">☰</button>
        <div>
            <h1>Mi Familia</h1>
            <p>Consulta los integrantes de tu familia y sus reservas.</p>
        </div>
        <div class="topbar-actions">
            <a href="index.php" class="topbar-btn">← Panel</a>
            <a href="logout.php" class="topbar-btn">➤ Salir</a>
        </div>
    </header>

    <section class="dashboard-content">
        <?php if (!$falleroActual): ?>
            <div class="alert alert-warning">Tu usuario no tiene una ficha de fallero asociada.</div>
        <?php elseif ($familiaId <= 0): ?>
            <div class="card-modern text-muted">No tienes una familia asociada todavía.</div>
        <?php else: ?>
            <div class="stats-grid user-stats-grid">
                <div class="stat-box blue">
                    <div class="stat-number"><?= count($miembros) ?></div>
                    <div class="stat-text">Integrantes</div>
                    <div class="stat-link"><?= e($falleroActual['familia_nombre'] ?: 'Mi familia') ?></div>
                </div>
                <div class="stat-box green">
                    <div class="stat-number"><?= count(array_filter($miembros, fn($m) => $m['estado'] === 'activo')) ?></div>
                    <div class="stat-text">Activos</div>
                    <div class="stat-link">Faller@s activos</div>
                </div>
                <div class="stat-box purple">
                    <div class="stat-number"><?= count($reservasFamilia) ?></div>
                    <div class="stat-text">Reservas familiares</div>
                    <div class="stat-link"><?= $esRepresentanteFamilia ? 'QR disponibles para representantes' : 'Últimos movimientos' ?></div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-xl-7">
                    <div class="card-modern table-card">
                        <h2 class="h5 mb-3">Integrantes</h2>
                        <table class="table align-middle">
                            <thead><tr><th>Fallero/a</th><th>Tipo</th><th>Estado</th><th>Contacto</th></tr></thead>
                            <tbody>
                            <?php foreach ($miembros as $m): ?>
                                <?php
                                    $esRepMiembro = ((int)$falleroActual['representante_fallero_id'] === (int)$m['id']);
                                    foreach ($representantesFamilia as $rep) {
                                        if ((int)$rep['fallero_id'] === (int)$m['id']) {
                                            $esRepMiembro = true;
                                            break;
                                        }
                                    }
                                ?>
                                <tr>
                                    <td>
                                        <strong><?= e($m['nombre'] . ' ' . $m['apellidos']) ?></strong>
                                        <?php if ($esRepMiembro): ?>
                                            <span class="badge bg-primary ms-1">representante</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= e($m['tipo']) ?></td>
                                    <td><span class="badge badge-estado <?= e($m['estado']) ?>"><?= e($m['estado']) ?></span></td>
                                    <td>
                                        <small class="text-muted d-block"><?= e($m['telefono'] ?: 'Sin teléfono') ?></small>
                                        <small class="text-muted d-block"><?= e($m['email'] ?: 'Sin email') ?></small>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-xl-5">
                    <div class="card-modern mb-4">
                        <h2 class="h5 mb-3">Datos de familia</h2>
                        <div class="detail-list">
                            <div><span>Familia</span><strong><?= e($falleroActual['familia_nombre'] ?: 'Sin nombre') ?></strong></div>
                            <div>
                                <span>Representante/s</span>
                                <strong>
                                    <?php if ($representantesFamilia): ?>
                                        <?= e(implode(' · ', array_map(fn($r) => $r['nombre_completo'], $representantesFamilia))) ?>
                                    <?php else: ?>
                                        <?= e($falleroActual['representante'] ?: 'Sin representante') ?>
                                    <?php endif; ?>
                                </strong>
                            </div>
                            <div><span>Observaciones</span><strong><?= e($falleroActual['observaciones'] ?: 'Sin observaciones') ?></strong></div>
                        </div>
                    </div>

                    <?php if (!$esRepresentanteFamilia): ?>
                        <div class="card-modern alert alert-light border mb-4">
                            Solo los representantes familiares pueden ver y enseñar los QR de todos los miembros de la familia.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-modern table-card mt-4">
                <h2 class="h5 mb-1">Reservas de mi familia</h2>
                <p class="text-muted mb-3">
                    <?php if ($esRepresentanteFamilia): ?>
                        Como representante, puedes ver los QR pagados de todos los integrantes de tu familia.
                    <?php else: ?>
                        Consulta las últimas reservas confirmadas de tu familia.
                    <?php endif; ?>
                </p>
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Fallero/a</th>
                            <th>Acto</th>
                            <th>Fecha</th>
                            <th>Opción</th>
                            <th>Pago</th>
                            <?php if ($esRepresentanteFamilia): ?>
                                <th>QR</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($reservasFamilia as $r): ?>
                        <tr>
                            <td><?= e($r['fallero']) ?></td>
                            <td><?= e($r['titulo']) ?></td>
                            <td><?= e(date('d/m/Y', strtotime($r['fecha']))) ?> <?= e(substr((string)$r['hora'], 0, 5)) ?></td>
                            <td><?= e($r['opcion'] ?: 'Sin opción') ?></td>
                            <td>
                                <?php if ((int)$r['pagada']): ?>
                                    <span class="badge bg-success">Pagada</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Pendiente</span>
                                <?php endif; ?>
                            </td>
                            <?php if ($esRepresentanteFamilia): ?>
                                <td>
                                    <?php if ((int)$r['pagada'] && !empty($r['qr_token'])): ?>
                                        <?php
                                            $qrUrl = $baseUrl . '/validar_qr.php?t=' . urlencode($r['qr_token']);
                                            $qrImg = 'https://api.qrserver.com/v1/create-qr-code/?size=420x420&data=' . urlencode($qrUrl);
                                        ?>
                                        <button type="button" class="btn btn-sm btn-primary" onclick="abrirQR('<?= e($qrImg) ?>')">Ver QR</button>
                                        <br><small><?= (int)$r['qr_usado'] ? 'Usado' : 'Un solo uso' ?></small>
                                    <?php elseif ((int)$r['pagada']): ?>
                                        <span class="text-muted">QR pendiente</span>
                                    <?php else: ?>
                                        <span class="text-muted">Pendiente de pago</span>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$reservasFamilia): ?>
                        <tr><td colspan="<?= $esRepresentanteFamilia ? 6 : 5 ?>" class="text-muted">Sin reservas familiares todavía.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
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
