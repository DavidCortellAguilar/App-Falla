<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/comidas_helpers.php';
ensure_comidas_multiples_schema($pdo);
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
        $esRepresentante = (int)$stmt->fetchColumn() === $falleroIdSesion;
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

$idsPermitidos = array_map(static fn($m) => (int)$m['id'], $miembrosPermitidos);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();

    try {
        $actoId = (int)($_POST['acto_id'] ?? 0);
        $seleccionados = is_array($_POST['seleccionados'] ?? null) ? $_POST['seleccionados'] : [];
        $opcionesPost = is_array($_POST['opciones_comida_multi'] ?? null) ? $_POST['opciones_comida_multi'] : [];
        $tieneInvitadosPost = is_array($_POST['tiene_invitados'] ?? null) ? $_POST['tiene_invitados'] : [];
        $nombresInvPost = is_array($_POST['invitado_nombre'] ?? null) ? $_POST['invitado_nombre'] : [];
        $tiposInvPost = is_array($_POST['invitado_tipo'] ?? null) ? $_POST['invitado_tipo'] : [];
        $opcionesInvPost = is_array($_POST['invitado_opciones_multi'] ?? null) ? $_POST['invitado_opciones_multi'] : [];

        if ($actoId > 0) {
            $stmt = $pdo->prepare("SELECT estado FROM actos WHERE id=:id LIMIT 1");
            $stmt->execute(['id' => $actoId]);
            if (strtolower((string)$stmt->fetchColumn()) !== 'abierto') {
                redirect('mis_actos.php');
            }

            $gruposActo = opciones_comida_por_categoria($pdo, $actoId);
            $actoTieneOpciones = count($gruposActo) > 0;
            $falleroIdsAGuardar = [];

            foreach ($idsPermitidos as $idPermitido) {
                $selecciones = is_array($opcionesPost[$idPermitido] ?? null) ? $opcionesPost[$idPermitido] : [];
                $tieneAlgunMenu = primera_opcion_de_grupos($selecciones) !== null;
                $marcado = !empty($seleccionados[$idPermitido]);
                if (($actoTieneOpciones && $tieneAlgunMenu) || (!$actoTieneOpciones && $marcado)) {
                    $falleroIdsAGuardar[] = $idPermitido;
                }
            }

            if ($falleroIdsAGuardar) {
                $pdo->beginTransaction();

                $stmtReserva = $pdo->prepare("
                    INSERT INTO reservas (fallero_id, acto_id, opcion_comida_id, estado, invitado_nombre, invitado_tipo, invitado_opcion_comida_id)
                    VALUES (:fallero_id, :acto_id, :opcion_id, 'confirmada', :invitado_nombre, :invitado_tipo, :invitado_opcion_id)
                    ON DUPLICATE KEY UPDATE
                        opcion_comida_id = VALUES(opcion_comida_id),
                        estado='confirmada',
                        invitado_nombre = VALUES(invitado_nombre),
                        invitado_tipo = VALUES(invitado_tipo),
                        invitado_opcion_comida_id = VALUES(invitado_opcion_comida_id),
                        updated_at=NOW()
                ");
                $stmtBuscaReserva = $pdo->prepare("SELECT id FROM reservas WHERE fallero_id=:fallero_id AND acto_id=:acto_id LIMIT 1");
                $stmtBorraInv = $pdo->prepare("DELETE FROM reserva_invitados WHERE reserva_id=:reserva_id");
                $stmtInv = $pdo->prepare("INSERT INTO reserva_invitados (reserva_id, nombre, tipo, opcion_comida_id) VALUES (:reserva_id, :nombre, :tipo, :opcion_comida_id)");

                foreach ($falleroIdsAGuardar as $falleroIdReserva) {
                    $selecciones = is_array($opcionesPost[$falleroIdReserva] ?? null) ? $opcionesPost[$falleroIdReserva] : [];
                    $primerMenu = primera_opcion_de_grupos($selecciones);
                    $primerMenu = opcion_comida_valida($pdo, $primerMenu, $actoId)['id'] ?? null;

                    $invitados = [];
                    if (!empty($tieneInvitadosPost[$falleroIdReserva])) {
                        $nombres = is_array($nombresInvPost[$falleroIdReserva] ?? null) ? $nombresInvPost[$falleroIdReserva] : [];
                        $tipos = is_array($tiposInvPost[$falleroIdReserva] ?? null) ? $tiposInvPost[$falleroIdReserva] : [];
                        $opcionesInv = is_array($opcionesInvPost[$falleroIdReserva] ?? null) ? $opcionesInvPost[$falleroIdReserva] : [];

                        foreach ($nombres as $i => $nombreRaw) {
                            $nombre = trim((string)$nombreRaw);
                            if ($nombre === '') continue;
                            $tipo = (string)($tipos[$i] ?? 'adulto');
                            if (!in_array($tipo, ['adulto', 'infantil'], true)) $tipo = 'adulto';

                            $seleccionesInvitado = [];
                            foreach ($opcionesInv as $categoria => $valoresPorInvitado) {
                                if (is_array($valoresPorInvitado)) {
                                    $seleccionesInvitado[$categoria] = $valoresPorInvitado[$i] ?? '';
                                }
                            }
                            $primerInvMenu = primera_opcion_de_grupos($seleccionesInvitado);
                            $primerInvMenu = opcion_comida_valida($pdo, $primerInvMenu, $actoId)['id'] ?? null;
                            $invitados[] = ['nombre' => $nombre, 'tipo' => $tipo, 'opciones' => $seleccionesInvitado, 'primer_menu' => $primerInvMenu];
                        }
                    }

                    $primerInvitado = $invitados[0] ?? null;
                    $stmtReserva->execute([
                        'fallero_id' => $falleroIdReserva,
                        'acto_id' => $actoId,
                        'opcion_id' => $primerMenu,
                        'invitado_nombre' => $primerInvitado['nombre'] ?? null,
                        'invitado_tipo' => $primerInvitado['tipo'] ?? null,
                        'invitado_opcion_id' => $primerInvitado['primer_menu'] ?? null,
                    ]);

                    $stmtBuscaReserva->execute(['fallero_id' => $falleroIdReserva, 'acto_id' => $actoId]);
                    $reservaId = (int)$stmtBuscaReserva->fetchColumn();
                    if ($reservaId > 0) {
                        guardar_opciones_reserva($pdo, $reservaId, $actoId, $selecciones);
                        $stmtBorraInv->execute(['reserva_id' => $reservaId]);
                        foreach ($invitados as $inv) {
                            $stmtInv->execute([
                                'reserva_id' => $reservaId,
                                'nombre' => $inv['nombre'],
                                'tipo' => $inv['tipo'],
                                'opcion_comida_id' => $inv['primer_menu'],
                            ]);
                            $reservaInvitadoId = (int)$pdo->lastInsertId();
                            if ($reservaInvitadoId > 0) {
                                guardar_opciones_invitado($pdo, $reservaInvitadoId, $actoId, $inv['opciones']);
                            }
                        }
                    }
                }

                $pdo->commit();
                log_activity($pdo, 'create', 'reservas', 'Reservas familiares creadas o actualizadas en bloque');
            }
        }
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        $_SESSION['flash_error'] = 'No se pudo guardar la reserva. Revisa que esté importado el SQL de comidas múltiples.';
    }

    redirect('mis_actos.php');
}

$actos = $pdo->query("SELECT * FROM actos ORDER BY FIELD(estado,'abierto','cerrado','cancelado'), fecha ASC, hora ASC")->fetchAll();
$reservasActuales = [];
$opcionesPorReserva = [];
$invitadosPorReserva = [];
$opcionesPorInvitado = [];

if ($idsPermitidos) {
    $placeholders = implode(',', array_fill(0, count($idsPermitidos), '?'));
    $stmt = $pdo->prepare("SELECT r.*, oc.nombre AS opcion_nombre FROM reservas r LEFT JOIN opciones_comida oc ON oc.id = r.opcion_comida_id WHERE r.fallero_id IN ($placeholders)");
    $stmt->execute($idsPermitidos);
    $reservas = $stmt->fetchAll();
    foreach ($reservas as $r) $reservasActuales[(int)$r['acto_id']][(int)$r['fallero_id']] = $r;
    $reservaIds = array_map(static fn($r) => (int)$r['id'], $reservas);

    if ($reservaIds) {
        $ph = implode(',', array_fill(0, count($reservaIds), '?'));
        $stmt = $pdo->prepare("SELECT ro.*, oc.nombre FROM reserva_opciones ro INNER JOIN opciones_comida oc ON oc.id=ro.opcion_comida_id WHERE ro.reserva_id IN ($ph)");
        $stmt->execute($reservaIds);
        foreach ($stmt->fetchAll() as $ro) $opcionesPorReserva[(int)$ro['reserva_id']][$ro['categoria']] = (int)$ro['opcion_comida_id'];

        $stmt = $pdo->prepare("SELECT ri.* FROM reserva_invitados ri WHERE ri.reserva_id IN ($ph) ORDER BY ri.id ASC");
        $stmt->execute($reservaIds);
        $invitados = $stmt->fetchAll();
        foreach ($invitados as $inv) $invitadosPorReserva[(int)$inv['reserva_id']][] = $inv;
        $invIds = array_map(static fn($i) => (int)$i['id'], $invitados);
        if ($invIds) {
            $iph = implode(',', array_fill(0, count($invIds), '?'));
            $stmt = $pdo->prepare("SELECT * FROM reserva_invitado_opciones WHERE reserva_invitado_id IN ($iph)");
            $stmt->execute($invIds);
            foreach ($stmt->fetchAll() as $io) $opcionesPorInvitado[(int)$io['reserva_invitado_id']][$io['categoria']] = (int)$io['opcion_comida_id'];
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
        <div class="topbar-actions"><a href="index.php" class="topbar-btn">← Panel</a><a href="logout.php" class="topbar-btn">➤ Salir</a></div>
    </header>

    <section class="dashboard-content">
        <?php if (!empty($_SESSION['flash_error'])): ?><div class="alert alert-danger"><?= e($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?></div><?php endif; ?>
        <?php if (!$falleroActual): ?><div class="alert alert-warning">Tu usuario no tiene una ficha de fallero asociada.</div><?php endif; ?>

        <div class="row g-4">
            <?php foreach ($actos as $acto): ?>
                <?php $gruposOpciones = opciones_comida_por_categoria($pdo, (int)$acto['id']); $actoAbierto = strtolower((string)$acto['estado']) === 'abierto'; ?>
                <div class="col-xl-6">
                    <div class="card-modern h-100 user-act-card <?= !$actoAbierto ? 'acto-cerrado-card' : '' ?>">
                        <div class="d-flex justify-content-between gap-3 align-items-start mb-2">
                            <div><h2 class="h5 mb-1"><?= e($acto['titulo']) ?></h2><p class="text-muted mb-1"><?= e($acto['fecha']) ?> <?= e(substr((string)$acto['hora'],0,5)) ?> · <?= e($acto['ubicacion']) ?></p></div>
                            <a href="acto_detalle.php?id=<?= (int)$acto['id'] ?>" class="detail-pill-btn">Ver detalle</a>
                        </div>
                        <p><?= e($acto['descripcion']) ?></p>
                        <?php if (!$actoAbierto): ?><div class="alert alert-danger small">Acto cerrado: no se pueden crear ni modificar reservas.</div><?php endif; ?>

                        <?php $mostrarFamiliares = false; if ($esRepresentante) { foreach ($miembrosPermitidos as $m) { if ((int)$m['id'] !== $falleroIdSesion && !empty($reservasActuales[(int)$acto['id']][(int)$m['id']])) { $mostrarFamiliares = true; break; } } } ?>
                        <?php if ($esRepresentante && count($miembrosPermitidos) > 1): ?>
                            <div class="family-toggle-box"><label class="family-toggle-label"><input class="form-check-input js-familiares-check" type="checkbox" <?= $mostrarFamiliares ? 'checked' : '' ?>><i class="fas fa-users"></i><span>¿Quieres apuntar algún familiar?</span></label></div>
                        <?php endif; ?>

                        <form method="post" class="family-reserve-form">
                            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                            <input type="hidden" name="acto_id" value="<?= (int)$acto['id'] ?>">
                            <div class="family-reserve-list">
                                <?php foreach ($miembrosPermitidos as $miembro): ?>
                                    <?php
                                    $mid = (int)$miembro['id']; $esMiReserva = $mid === $falleroIdSesion;
                                    $reserva = $reservasActuales[(int)$acto['id']][$mid] ?? null;
                                    $estaConfirmado = $reserva && $reserva['estado'] === 'confirmada';
                                    $seleccionesActuales = $reserva ? ($opcionesPorReserva[(int)$reserva['id']] ?? []) : [];
                                    if (!$seleccionesActuales && $reserva && !empty($reserva['opcion_comida_id'])) $seleccionesActuales['Comida'] = (int)$reserva['opcion_comida_id'];
                                    $invitadosActuales = $reserva ? ($invitadosPorReserva[(int)$reserva['id']] ?? []) : [];
                                    if (!$invitadosActuales && $reserva && !empty($reserva['invitado_nombre'])) $invitadosActuales[] = ['id'=>0,'nombre'=>$reserva['invitado_nombre'],'tipo'=>$reserva['invitado_tipo'] ?: 'adulto','opcion_comida_id'=>$reserva['invitado_opcion_comida_id'] ?? null];
                                    $textoOpciones = [];
                                    foreach ($gruposOpciones as $cat => $ops) foreach ($ops as $op) if (($seleccionesActuales[$cat] ?? 0) == (int)$op['id']) $textoOpciones[] = $cat . ': ' . $op['nombre'];
                                    ?>
                                    <div class="family-reserve-row <?= (!$esMiReserva && $esRepresentante) ? 'js-familiar-row' : 'js-self-row' ?>" style="<?= (!$esMiReserva && $esRepresentante && !$mostrarFamiliares) ? 'display:none;' : '' ?>">
                                        <div class="reserve-member">
                                            <label class="reserve-member-check"><input class="form-check-input js-reserva-check" type="checkbox" name="seleccionados[<?= $mid ?>]" value="1" <?= $estaConfirmado ? 'checked' : '' ?> <?= !$actoAbierto ? 'disabled' : '' ?>><span><strong><?= e($miembro['nombre'] . ' ' . $miembro['apellidos']) ?></strong><small class="text-muted d-block"><?= e($miembro['tipo']) ?></small></span></label>
                                            <?php if ($estaConfirmado): ?><span class="reservation-status is-in">✓ Apuntado<?= $textoOpciones ? ': ' . e(implode(' · ', $textoOpciones)) : '' ?></span><?php else: ?><span class="reservation-status is-out">Sin apuntar</span><?php endif; ?>
                                        </div>

                                        <div class="reserve-option">
                                            <?php if ($gruposOpciones): ?>
                                                <label class="small text-muted d-block mb-1">Opciones del fallero/a</label>
                                                <div class="multi-food-grid">
                                                    <?php foreach ($gruposOpciones as $categoria => $opciones): ?>
                                                        <div><label class="small fw-bold"><?= e($categoria) ?></label><select class="form-select js-menu-select" name="opciones_comida_multi[<?= $mid ?>][<?= e($categoria) ?>]" <?= !$actoAbierto ? 'disabled' : '' ?>><option value="">Selecciona una opción</option><?php foreach ($opciones as $opcion): ?><option value="<?= (int)$opcion['id'] ?>" <?= ((int)($seleccionesActuales[$categoria] ?? 0) === (int)$opcion['id']) ? 'selected' : '' ?>><?= e($opcion['nombre']) ?></option><?php endforeach; ?></select></div>
                                                    <?php endforeach; ?>
                                                </div>
                                                <small class="text-muted d-block mt-1">Elige una opción en cada bloque que necesites: comida, merienda, cena...</small>
                                            <?php else: ?>
                                                <span class="text-muted small">Sin opciones: marca el check para apuntar a esta persona.</span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="guest-section">
                                            <label class="guest-toggle"><input class="form-check-input js-invitado-check" type="checkbox" name="tiene_invitados[<?= $mid ?>]" value="1" <?= $invitadosActuales ? 'checked' : '' ?> <?= !$actoAbierto ? 'disabled' : '' ?>><span>Añadir invitado/s no fallero/s</span></label>
                                            <div class="js-invitado-fields" style="<?= $invitadosActuales ? '' : 'display:none;' ?>">
                                                <div class="guest-list">
                                                    <?php $guestRows = $invitadosActuales ?: [['id'=>0,'nombre'=>'','tipo'=>'adulto','opcion_comida_id'=>null]]; ?>
                                                    <?php foreach ($guestRows as $idx => $inv): ?>
                                                        <?php $selInv = $opcionesPorInvitado[(int)($inv['id'] ?? 0)] ?? []; if (!$selInv && !empty($inv['opcion_comida_id'])) $selInv['Comida'] = (int)$inv['opcion_comida_id']; ?>
                                                        <div class="guest-row">
                                                            <input class="form-control" name="invitado_nombre[<?= $mid ?>][]" placeholder="Nombre del invitado" value="<?= e($inv['nombre'] ?? '') ?>" <?= !$actoAbierto ? 'disabled' : '' ?>>
                                                            <select class="form-select" name="invitado_tipo[<?= $mid ?>][]" <?= !$actoAbierto ? 'disabled' : '' ?>><option value="adulto" <?= ($inv['tipo'] ?? '') === 'adulto' ? 'selected' : '' ?>>Adulto</option><option value="infantil" <?= ($inv['tipo'] ?? '') === 'infantil' ? 'selected' : '' ?>>Infantil</option></select>
                                                            <?php if ($gruposOpciones): ?><div class="guest-food-selects"><?php foreach ($gruposOpciones as $categoria => $opciones): ?><select class="form-select" name="invitado_opciones_multi[<?= $mid ?>][<?= e($categoria) ?>][]" <?= !$actoAbierto ? 'disabled' : '' ?>><option value=""><?= e($categoria) ?> invitado</option><?php foreach ($opciones as $opcion): ?><option value="<?= (int)$opcion['id'] ?>" <?= ((int)($selInv[$categoria] ?? 0) === (int)$opcion['id']) ? 'selected' : '' ?>><?= e($opcion['nombre']) ?></option><?php endforeach; ?></select><?php endforeach; ?></div><?php endif; ?>
                                                            <button type="button" class="btn btn-sm btn-outline-danger js-remove-guest" <?= !$actoAbierto ? 'disabled' : '' ?>>Quitar</button>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-outline-primary js-add-guest mt-2" <?= !$actoAbierto ? 'disabled' : '' ?>>+ Añadir otro invitado</button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="reserve-actions bulk-actions"><button class="btn btn-primary reserve-submit" name="accion" value="guardar" <?= !$actoAbierto ? 'disabled' : '' ?>><?= $esRepresentante ? 'Guardar reservas seleccionadas' : 'Guardar mi reserva' ?></button><small class="text-muted">Desde aquí solo se crean o actualizan reservas. Para cancelar, entra en “Mis reservas”.</small></div>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (!$actos): ?><div class="col-12"><div class="card-modern text-muted">No hay actos abiertos actualmente.</div></div><?php endif; ?>
        </div>
    </section>
</main>

<style>
.family-reserve-row{display:grid;grid-template-columns:1fr;gap:14px;align-items:start;overflow:hidden}.reserve-member-check{display:flex;align-items:flex-start;gap:10px;margin-bottom:6px;cursor:pointer}.reserve-member-check input{margin-top:4px}.bulk-actions{margin-top:16px;padding-top:14px;border-top:1px solid #e5e7eb;display:flex;align-items:center;gap:12px;flex-wrap:wrap}.family-reserve-form{display:flex;flex-direction:column;gap:12px}.reserve-option{max-width:100%}.multi-food-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:10px}.guest-section{margin-top:4px;padding-top:12px;border-top:1px dashed #e5e7eb}.guest-toggle{display:flex;align-items:center;gap:8px;font-weight:700;color:#111827;margin-bottom:10px}.js-invitado-fields{padding:12px;border:1px solid #e5e7eb;border-radius:16px;background:#fafafa;max-width:100%}.guest-list{display:flex;flex-direction:column;gap:10px}.guest-row{display:grid;grid-template-columns:1.2fr 120px minmax(220px,1.4fr) auto;gap:10px;align-items:start}.guest-food-selects{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:8px}.reserve-submit{min-width:170px}.form-check-input{margin-top:0}@media (max-width:900px){.guest-row{grid-template-columns:1fr}.reserve-submit{width:100%}}
</style>
<script>
document.addEventListener('change',function(e){if(e.target.classList.contains('js-familiares-check')){const card=e.target.closest('.user-act-card');if(!card)return;card.querySelectorAll('.js-familiar-row').forEach(row=>row.style.display=e.target.checked?'':'none');return;}if(e.target.classList.contains('js-menu-select')){const row=e.target.closest('.family-reserve-row');const check=row?row.querySelector('.js-reserva-check'):null;if(check){const has=[...row.querySelectorAll('.js-menu-select')].some(s=>s.value!=='');check.checked=has;}return;}if(!e.target.classList.contains('js-invitado-check'))return;const fields=e.target.closest('.guest-section')?.querySelector('.js-invitado-fields');if(fields)fields.style.display=e.target.checked?'':'none';});
document.addEventListener('click',function(e){const addBtn=e.target.closest('.js-add-guest');if(addBtn){const list=addBtn.closest('.js-invitado-fields').querySelector('.guest-list');const first=list.querySelector('.guest-row');const clone=first.cloneNode(true);clone.querySelectorAll('input').forEach(i=>i.value='');clone.querySelectorAll('select').forEach(s=>s.selectedIndex=0);list.appendChild(clone);return;}const removeBtn=e.target.closest('.js-remove-guest');if(removeBtn){const list=removeBtn.closest('.guest-list');const rows=list.querySelectorAll('.guest-row');if(rows.length>1){removeBtn.closest('.guest-row').remove();}else{removeBtn.closest('.guest-row').querySelectorAll('input').forEach(i=>i.value='');removeBtn.closest('.guest-row').querySelectorAll('select').forEach(s=>s.selectedIndex=0);}}});
</script>
<?php include __DIR__ . '/footer.php'; ?>
