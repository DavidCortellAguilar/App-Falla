<?php
require_once __DIR__ . '/config.php';
require_login();
$page_title = 'Actos disponibles';

$falleroIdSesion = (int) ($_SESSION['fallero_id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM falleros WHERE id=:id LIMIT 1");
$stmt->execute(['id' => $falleroIdSesion]);
$falleroActual = $stmt->fetch();

$familiaId = (int) ($falleroActual['familia_id'] ?? 0);
$esRepresentante = false;
$miembrosPermitidos = [];

if ($familiaId > 0) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM familia_representantes WHERE familia_id=:familia_id AND fallero_id=:fallero_id");
    $stmt->execute(['familia_id' => $familiaId, 'fallero_id' => $falleroIdSesion]);
    $esRepresentante = (int) $stmt->fetchColumn() > 0;
    if (!$esRepresentante) {
        $stmt = $pdo->prepare("SELECT representante_fallero_id FROM familias WHERE id=:id");
        $stmt->execute(['id' => $familiaId]);
        $representanteId = (int) $stmt->fetchColumn();
        $esRepresentante = $representanteId === $falleroIdSesion;
    }
}

if ($esRepresentante && $familiaId > 0) {
    $stmt = $pdo->prepare("SELECT id, nombre, apellidos, tipo FROM falleros WHERE familia_id=:familia_id AND estado='activo' ORDER BY apellidos, nombre");
    $stmt->execute(['familia_id' => $familiaId]);
    $miembrosPermitidos = $stmt->fetchAll();
} elseif ($falleroActual) {
    $miembrosPermitidos = [[
        'id' => $falleroActual['id'],
        'nombre' => $falleroActual['nombre'],
        'apellidos' => $falleroActual['apellidos'],
        'tipo' => $falleroActual['tipo'],
    ]];
}

$idsPermitidos = array_map(static fn($m) => (int) $m['id'], $miembrosPermitidos);

function opcion_valida(PDO $pdo, ?int $opcionId, int $actoId): ?int {
    if (!$opcionId) return null;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM opciones_comida WHERE id=:id AND acto_id=:acto_id AND is_active=1");
    $stmt->execute(['id' => $opcionId, 'acto_id' => $actoId]);
    return ((int) $stmt->fetchColumn() > 0) ? $opcionId : null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();

    try {
        $actoId = (int) ($_POST['acto_id'] ?? 0);
        $falleroIdReserva = (int) ($_POST['fallero_id'] ?? 0);
        $opcionId = !empty($_POST['opcion_comida_id']) ? (int) $_POST['opcion_comida_id'] : null;

        if ($actoId > 0 && $falleroIdReserva > 0 && in_array($falleroIdReserva, $idsPermitidos, true)) {
            $stmt = $pdo->prepare("SELECT estado FROM actos WHERE id=:id LIMIT 1");
            $stmt->execute(['id' => $actoId]);
            if (strtolower((string)$stmt->fetchColumn()) !== 'abierto') {
                redirect('mis_actos.php');
            }

            $opcionId = opcion_valida($pdo, $opcionId, $actoId);

            $invitados = [];
            if (isset($_POST['tiene_invitados'])) {
                $nombres = $_POST['invitado_nombre'] ?? [];
                $tipos = $_POST['invitado_tipo'] ?? [];
                $opcionesInvitado = $_POST['invitado_opcion_comida_id'] ?? [];

                if (!is_array($nombres)) $nombres = [$nombres];
                if (!is_array($tipos)) $tipos = [$tipos];
                if (!is_array($opcionesInvitado)) $opcionesInvitado = [$opcionesInvitado];

                foreach ($nombres as $i => $nombreRaw) {
                    $nombre = trim((string)$nombreRaw);
                    if ($nombre === '') continue;
                    $tipo = (string)($tipos[$i] ?? '');
                    if (!in_array($tipo, ['adulto', 'infantil'], true)) $tipo = 'adulto';
                    $opcionInvId = !empty($opcionesInvitado[$i]) ? (int)$opcionesInvitado[$i] : null;
                    $opcionInvId = opcion_valida($pdo, $opcionInvId, $actoId);
                    $invitados[] = [
                        'nombre' => $nombre,
                        'tipo' => $tipo,
                        'opcion_comida_id' => $opcionInvId,
                    ];
                }
            }

            $pdo->beginTransaction();

            $stmt = $pdo->prepare("\n                INSERT INTO reservas (fallero_id, acto_id, opcion_comida_id, estado, invitado_nombre, invitado_tipo, invitado_opcion_comida_id)\n                VALUES (:fallero_id, :acto_id, :opcion_id, 'confirmada', :invitado_nombre, :invitado_tipo, :invitado_opcion_id)\n                ON DUPLICATE KEY UPDATE\n                    opcion_comida_id = VALUES(opcion_comida_id),\n                    estado='confirmada',\n                    invitado_nombre = VALUES(invitado_nombre),\n                    invitado_tipo = VALUES(invitado_tipo),\n                    invitado_opcion_comida_id = VALUES(invitado_opcion_comida_id),\n                    updated_at=NOW()\n            ");
            $primerInvitado = $invitados[0] ?? null;
            $stmt->execute([
                'fallero_id' => $falleroIdReserva,
                'acto_id' => $actoId,
                'opcion_id' => $opcionId,
                'invitado_nombre' => $primerInvitado['nombre'] ?? null,
                'invitado_tipo' => $primerInvitado['tipo'] ?? null,
                'invitado_opcion_id' => $primerInvitado['opcion_comida_id'] ?? null,
            ]);

            $stmt = $pdo->prepare("SELECT id FROM reservas WHERE fallero_id=:fallero_id AND acto_id=:acto_id LIMIT 1");
            $stmt->execute(['fallero_id' => $falleroIdReserva, 'acto_id' => $actoId]);
            $reservaId = (int) $stmt->fetchColumn();

            if ($reservaId > 0) {
                $pdo->prepare("DELETE FROM reserva_invitados WHERE reserva_id=:reserva_id")->execute(['reserva_id' => $reservaId]);
                if ($invitados) {
                    $ins = $pdo->prepare("INSERT INTO reserva_invitados (reserva_id, nombre, tipo, opcion_comida_id) VALUES (:reserva_id, :nombre, :tipo, :opcion_comida_id)");
                    foreach ($invitados as $inv) {
                        $ins->execute([
                            'reserva_id' => $reservaId,
                            'nombre' => $inv['nombre'],
                            'tipo' => $inv['tipo'],
                            'opcion_comida_id' => $inv['opcion_comida_id'],
                        ]);
                    }
                }
            }

            $pdo->commit();
            log_activity($pdo, 'create', 'reservas', 'Reserva realizada o actualizada por fallero o representante familiar');
        }
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        $_SESSION['flash_error'] = 'No se pudo guardar la reserva. Revisa que hayas importado el SQL de actualización.';
    }

    redirect('mis_actos.php');
}

$actos = $pdo->query("SELECT * FROM actos ORDER BY FIELD(estado,'abierto','cerrado','cancelado'), fecha ASC, hora ASC")->fetchAll();

$reservasActuales = [];
$invitadosPorReserva = [];
if ($idsPermitidos) {
    $placeholders = implode(',', array_fill(0, count($idsPermitidos), '?'));
    $stmt = $pdo->prepare("\n        SELECT r.*, oc.nombre AS opcion_nombre\n        FROM reservas r\n        LEFT JOIN opciones_comida oc ON oc.id = r.opcion_comida_id\n        WHERE r.fallero_id IN ($placeholders)\n    ");
    $stmt->execute($idsPermitidos);
    $reservas = $stmt->fetchAll();
    foreach ($reservas as $r) {
        $reservasActuales[(int)$r['acto_id']][(int)$r['fallero_id']] = $r;
    }

    $reservaIds = array_map(static fn($r) => (int)$r['id'], $reservas);
    if ($reservaIds) {
        $ph = implode(',', array_fill(0, count($reservaIds), '?'));
        $stmt = $pdo->prepare("\n            SELECT ri.*, oc.nombre AS opcion_nombre\n            FROM reserva_invitados ri\n            LEFT JOIN opciones_comida oc ON oc.id = ri.opcion_comida_id\n            WHERE ri.reserva_id IN ($ph)\n            ORDER BY ri.id ASC\n        ");
        $stmt->execute($reservaIds);
        foreach ($stmt->fetchAll() as $inv) {
            $invitadosPorReserva[(int)$inv['reserva_id']][] = $inv;
        }
    }
}

include __DIR__ . '/header.php';
include __DIR__ . '/sidebar.php';
?>

<main class="main-dashboard">
    <header class="dashboard-topbar">
        <button class="mobile-menu-btn" type="button" onclick="toggleSidebar()">☰</button>
        <div>
            <h1>Actos disponibles</h1>
            <p><?= $esRepresentante ? 'Como representante familiar puedes apuntar a todos los miembros de tu familia.' : 'Actos abiertos para apuntarte.' ?></p>
        </div>
        <div class="topbar-actions">
            <a href="index.php" class="topbar-btn">← Panel</a>
            <a href="logout.php" class="topbar-btn">➤ Salir</a>
        </div>
    </header>

    <section class="dashboard-content">
        <?php if (!empty($_SESSION['flash_error'])): ?>
            <div class="alert alert-danger"><?= e($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?></div>
        <?php endif; ?>
        <?php if (!$falleroActual): ?>
            <div class="alert alert-warning">Tu usuario no tiene una ficha de fallero asociada.</div>
        <?php endif; ?>

        <div class="row g-4">
            <?php foreach ($actos as $acto): ?>
                <?php
                $stmt = $pdo->prepare("SELECT * FROM opciones_comida WHERE acto_id=:id AND is_active=1 ORDER BY nombre");
                $stmt->execute(['id' => $acto['id']]);
                $opciones = $stmt->fetchAll();
                $actoAbierto = strtolower((string)$acto['estado']) === 'abierto';
                ?>
                <div class="col-xl-6">
                    <div class="card-modern h-100 user-act-card <?= !$actoAbierto ? 'acto-cerrado-card' : '' ?>">
                        <div class="d-flex justify-content-between gap-3 align-items-start mb-2">
                            <div>
                                <h2 class="h5 mb-1"><?= e($acto['titulo']) ?></h2>
                                <p class="text-muted mb-1"><?= e($acto['fecha']) ?> <?= e(substr((string)$acto['hora'],0,5)) ?> · <?= e($acto['ubicacion']) ?></p>
                            </div>
                            <a href="acto_detalle.php?id=<?= (int) $acto['id'] ?>" class="detail-pill-btn">Ver detalle</a>
                        </div>
                        <p><?= e($acto['descripcion']) ?></p>
                        <?php if (!$actoAbierto): ?><div class="alert alert-danger small">Acto cerrado: no se pueden crear ni modificar reservas.</div><?php endif; ?>

                        <?php
                        $mostrarFamiliares = false;
                        if ($esRepresentante) {
                            foreach ($miembrosPermitidos as $miembroCheck) {
                                if ((int)$miembroCheck['id'] === $falleroIdSesion) continue;
                                if (!empty($reservasActuales[(int)$acto['id']][(int)$miembroCheck['id']])) {
                                    $mostrarFamiliares = true;
                                    break;
                                }
                            }
                        }
                        ?>

                        <?php if ($esRepresentante && count($miembrosPermitidos) > 1): ?>
                            <div class="family-toggle-box">
                                <label class="family-toggle-label">
                                    <input class="form-check-input js-familiares-check" type="checkbox" <?= $mostrarFamiliares ? 'checked' : '' ?>>
                                    <i class="fas fa-users"></i>
                                    <span>¿Quieres apuntar algún familiar?</span>
                                </label>
                            </div>
                        <?php endif; ?>

                        <div class="family-reserve-list">
                            <?php foreach ($miembrosPermitidos as $miembro): ?>
                                <?php
                                $esMiReserva = (int)$miembro['id'] === $falleroIdSesion;
                                $reserva = $reservasActuales[(int)$acto['id']][(int)$miembro['id']] ?? null;
                                $invitadosActuales = $reserva ? ($invitadosPorReserva[(int)$reserva['id']] ?? []) : [];
                                if (!$invitadosActuales && $reserva && !empty($reserva['invitado_nombre'])) {
                                    $invitadosActuales[] = [
                                        'nombre' => $reserva['invitado_nombre'],
                                        'tipo' => $reserva['invitado_tipo'] ?: 'adulto',
                                        'opcion_comida_id' => $reserva['invitado_opcion_comida_id'] ?? null,
                                    ];
                                }
                                ?>
                                <form method="post" class="family-reserve-row <?= (!$esMiReserva && $esRepresentante) ? 'js-familiar-row' : 'js-self-row' ?>" style="<?= (!$esMiReserva && $esRepresentante && !$mostrarFamiliares) ? 'display:none;' : '' ?>">
                                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                    <input type="hidden" name="acto_id" value="<?= (int) $acto['id'] ?>">
                                    <input type="hidden" name="fallero_id" value="<?= (int) $miembro['id'] ?>">

                                    <div class="reserve-member">
                                        <strong><?= e($miembro['nombre'] . ' ' . $miembro['apellidos']) ?></strong>
                                        <small class="text-muted d-block"><?= e($miembro['tipo']) ?></small>
                                        <?php if ($reserva && $reserva['estado'] === 'confirmada'): ?>
                                            <span class="reservation-status is-in">✓ Apuntado<?= $reserva['opcion_nombre'] ? ': ' . e($reserva['opcion_nombre']) : '' ?></span>
                                        <?php else: ?>
                                            <span class="reservation-status is-out">Pendiente de apuntar</span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="reserve-option">
                                        <?php if ($opciones): ?>
                                            <label class="small text-muted d-block mb-1">Menú del fallero/a</label>
                                            <select class="form-select" name="opcion_comida_id" <?= !$actoAbierto ? 'disabled' : '' ?>>
                                                <?php foreach ($opciones as $opcion): ?>
                                                    <option value="<?= (int) $opcion['id'] ?>" <?= $reserva && (int)$reserva['opcion_comida_id'] === (int)$opcion['id'] ? 'selected' : '' ?>>
                                                        <?= e($opcion['nombre']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        <?php else: ?>
                                            <input type="hidden" name="opcion_comida_id" value="">
                                            <span class="text-muted small">Sin opciones</span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="guest-section">
                                        <label class="guest-toggle">
                                            <input class="form-check-input js-invitado-check" type="checkbox" name="tiene_invitados" value="1" <?= $invitadosActuales ? 'checked' : '' ?> <?= !$actoAbierto ? 'disabled' : '' ?>>
                                            <span>Añadir invitado/s no fallero/s</span>
                                        </label>

                                        <div class="js-invitado-fields" style="<?= $invitadosActuales ? '' : 'display:none;' ?>">
                                            <div class="guest-list">
                                                <?php $guestRows = $invitadosActuales ?: [['nombre'=>'','tipo'=>'adulto','opcion_comida_id'=>null]]; ?>
                                                <?php foreach ($guestRows as $inv): ?>
                                                    <div class="guest-row">
                                                        <input class="form-control" name="invitado_nombre[]" placeholder="Nombre del invitado" value="<?= e($inv['nombre'] ?? '') ?>" <?= !$actoAbierto ? 'disabled' : '' ?>>
                                                        <select class="form-select" name="invitado_tipo[]" <?= !$actoAbierto ? 'disabled' : '' ?>>
                                                            <option value="adulto" <?= ($inv['tipo'] ?? '') === 'adulto' ? 'selected' : '' ?>>Adulto</option>
                                                            <option value="infantil" <?= ($inv['tipo'] ?? '') === 'infantil' ? 'selected' : '' ?>>Infantil</option>
                                                        </select>
                                                        <?php if ($opciones): ?>
                                                            <select class="form-select" name="invitado_opcion_comida_id[]" <?= !$actoAbierto ? 'disabled' : '' ?>>
                                                                <option value="">Menú del invitado</option>
                                                                <?php foreach ($opciones as $opcion): ?>
                                                                    <option value="<?= (int) $opcion['id'] ?>" <?= (int)($inv['opcion_comida_id'] ?? 0) === (int)$opcion['id'] ? 'selected' : '' ?>>
                                                                        <?= e($opcion['nombre']) ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        <?php endif; ?>
                                                        <button type="button" class="btn btn-sm btn-outline-danger js-remove-guest" <?= !$actoAbierto ? 'disabled' : '' ?>>Quitar</button>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-primary js-add-guest mt-2" <?= !$actoAbierto ? 'disabled' : '' ?>>+ Añadir otro invitado</button>
                                        </div>
                                    </div>

                                    <button class="btn btn-primary reserve-submit" <?= !$actoAbierto ? 'disabled' : '' ?>>
                                        <?= $reserva ? 'Actualizar' : 'Apuntar' ?>
                                    </button>
                                </form>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (!$actos): ?>
                <div class="col-12"><div class="card-modern text-muted">No hay actos abiertos actualmente.</div></div>
            <?php endif; ?>
        </div>
    </section>
</main>

<style>
.family-reserve-row{
    display:grid;
    grid-template-columns:1fr minmax(180px,240px);
    gap:14px;
    align-items:start;
    overflow:hidden;
}
.family-reserve-row .reserve-member{grid-column:1 / -1;}
.family-reserve-row .reserve-option{grid-column:1 / -1;max-width:320px;}
.guest-section{grid-column:1 / -1;margin-top:4px;padding-top:12px;border-top:1px dashed #e5e7eb;}
.guest-toggle{display:flex;align-items:center;gap:8px;font-weight:700;color:#111827;margin-bottom:10px;}
.js-invitado-fields{padding:12px;border:1px solid #e5e7eb;border-radius:16px;background:#fafafa;max-width:100%;}
.guest-list{display:flex;flex-direction:column;gap:10px;}
.guest-row{display:grid;grid-template-columns:1.4fr 130px 1fr auto;gap:10px;align-items:center;}
.reserve-submit{grid-column:1 / -1;justify-self:start;min-width:170px;}
.form-check-input{margin-top:0;}
@media (max-width: 900px){
    .family-reserve-row{grid-template-columns:1fr;}
    .guest-row{grid-template-columns:1fr;}
    .reserve-submit{width:100%;}
}
</style>
<script>
document.addEventListener('change', function(e){
    if (e.target.classList.contains('js-familiares-check')) {
        const card = e.target.closest('.user-act-card');
        if (!card) return;
        card.querySelectorAll('.js-familiar-row').forEach(function(row){
            row.style.display = e.target.checked ? '' : 'none';
        });
        return;
    }

    if (!e.target.classList.contains('js-invitado-check')) return;
    const section = e.target.closest('.guest-section');
    const fields = section ? section.querySelector('.js-invitado-fields') : null;
    if (fields) fields.style.display = e.target.checked ? '' : 'none';
});

document.addEventListener('click', function(e){
    const addBtn = e.target.closest('.js-add-guest');
    if (addBtn) {
        const fields = addBtn.closest('.js-invitado-fields');
        const list = fields.querySelector('.guest-list');
        const first = list.querySelector('.guest-row');
        const clone = first.cloneNode(true);
        clone.querySelectorAll('input').forEach(input => input.value = '');
        clone.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
        list.appendChild(clone);
        return;
    }

    const removeBtn = e.target.closest('.js-remove-guest');
    if (removeBtn) {
        const list = removeBtn.closest('.guest-list');
        const rows = list.querySelectorAll('.guest-row');
        if (rows.length > 1) {
            removeBtn.closest('.guest-row').remove();
        } else {
            removeBtn.closest('.guest-row').querySelectorAll('input').forEach(input => input.value = '');
            removeBtn.closest('.guest-row').querySelectorAll('select').forEach(select => select.selectedIndex = 0);
        }
    }
});
</script>

<?php include __DIR__ . '/footer.php'; ?>
