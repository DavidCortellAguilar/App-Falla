<?php
require_once __DIR__ . '/config.php';
require_login();

$page_title = is_admin() ? 'Vista global' : 'Mi panel';

if (is_admin()) {
    $fallerosActivos = $pdo->query("SELECT COUNT(*) FROM falleros WHERE estado = 'activo'")->fetchColumn();
    $fallerosPendientes = $pdo->query("SELECT COUNT(*) FROM falleros WHERE estado = 'pendiente'")->fetchColumn();
    $familias = $pdo->query("SELECT COUNT(*) FROM familias")->fetchColumn();
    $actos = $pdo->query("SELECT COUNT(*) FROM actos")->fetchColumn();

    $reservasTodas = $pdo->query("SELECT (SELECT COUNT(*) FROM reservas WHERE estado = 'confirmada') + (SELECT COUNT(*) FROM reserva_invitados ri INNER JOIN reservas r ON r.id=ri.reserva_id WHERE r.estado = 'confirmada')")->fetchColumn();

    $actosCalendario = $pdo->query("
        SELECT id, titulo, fecha, hora, tipo, estado
        FROM actos
        WHERE estado <> 'cancelado'
        ORDER BY fecha ASC, hora ASC
    ")->fetchAll(PDO::FETCH_ASSOC);

    $eventos = [];

    foreach ($actosCalendario as $acto) {
        $start = $acto['fecha'];

        if (!empty($acto['hora'])) {
            $start .= 'T' . substr($acto['hora'], 0, 5) . ':00';
        }

        $eventos[] = [
            'id' => $acto['id'],
            'title' => $acto['titulo'],
            'start' => $start,
            'url' => 'acto_detalle.php?id=' . $acto['id'],
            'className' => 'evento-' . $acto['tipo'],
            'extendedProps' => [
                'tipo' => $acto['tipo'],
                'estado' => $acto['estado'],
            ],
        ];
    }
}

include __DIR__ . '/header.php';
include __DIR__ . '/sidebar.php';
?>

<?php if (is_admin()): ?>
<main class="main-dashboard">
    <header class="dashboard-topbar">
        <button class="mobile-menu-btn" type="button" onclick="toggleSidebar()">☰</button>
    
        <div style="flex:1;">
            <h1>Vista global</h1>
        </div>
    
        <div class="topbar-actions">
            <a href="logout.php" class="topbar-btn">➤ Salir</a>
        </div>
    </header>

    <section class="dashboard-content">
        <h2 class="section-title">Faller@s y actos</h2>

        <div class="stats-grid">
            <a href="falleros.php" class="stat-box green">
                <div class="stat-number"><?= e($fallerosActivos) ?></div>
                <div class="stat-text">Faller@s activos</div>
                <div class="stat-link">Ver censo →</div>
            </a>

            <a href="falleros_pendientes.php" class="stat-box orange">
                <div class="stat-number"><?= e($fallerosPendientes) ?></div>
                <div class="stat-text">Pendientes de aprobar</div>
                <div class="stat-link">Gestionar →</div>
            </a>

            <a href="familias.php" class="stat-box blue">
                <div class="stat-number"><?= e($familias) ?></div>
                <div class="stat-text">Familias</div>
                <div class="stat-link">Ver familias →</div>
            </a>

            <a href="actos.php" class="stat-box purple">
                <div class="stat-number"><?= e($actos) ?></div>
                <div class="stat-text">Actos creados</div>
                <div class="stat-link">Ver actos →</div>
            </a>
        </div>

        <h2 class="section-title" style="margin-top:34px;">Acciones rápidas</h2>

        <div class="quick-actions">
            <a href="falleros.php" class="quick-action">👤 Añadir faller@</a>
            <a href="familias.php" class="quick-action">👨‍👩‍👧 Nueva familia</a>
            <a href="actos.php" class="quick-action">➕ Crear acto</a>
            <a href="avisos.php" class="quick-action">📣 Enviar aviso</a>
        </div>

        <h2 class="section-title" style="margin-top:42px;">Reservas y calendario</h2>

        <div class="admin-reservas-calendario" style="display:grid; grid-template-columns:1fr 3fr; gap:20px; align-items:start;">
            <a href="reservas.php" class="stat-box blue" style="height:160px; min-height:160px;">
                <div class="stat-number"><?= e($reservasTodas) ?></div>
                <div class="stat-text">Reservas confirmadas</div>
                <div class="stat-link">Ver todas →</div>
            </a>

            <div class="calendar-card" style="padding:20px;">
                <div class="panel-card-header">
                    <div>
                        <span class="eyebrow">Agenda fallera</span>
                        <h2>Calendario de actos</h2>
                    </div>
                    <a href="calendario.php" class="soft-link-btn">Ver completo</a>
                </div>

                <div id="calendar"></div>
            </div>
        </div>
    </section>
</main>

<?php else: ?>

<?php
$falleroIdSesion = (int) ($_SESSION['fallero_id'] ?? 0);
$inicioMes = date('Y-m-01');
$finMes = date('Y-m-t');

$stmt = $pdo->prepare("
    SELECT f.*, fa.nombre AS familia_nombre, fa.representante_fallero_id
    FROM falleros f
    LEFT JOIN familias fa ON fa.id = f.familia_id
    WHERE f.id = :id
    LIMIT 1
");
$stmt->execute(['id' => $falleroIdSesion]);
$falleroActual = $stmt->fetch();

$familiaId = (int) ($falleroActual['familia_id'] ?? 0);
$miembrosFamilia = [];

if ($familiaId > 0) {
    $stmt = $pdo->prepare("
        SELECT id, nombre, apellidos, tipo, estado
        FROM falleros
        WHERE familia_id = :familia_id
        ORDER BY apellidos, nombre
    ");
    $stmt->execute(['familia_id' => $familiaId]);
    $miembrosFamilia = $stmt->fetchAll();
}

$actosMes = $pdo->prepare("
    SELECT COUNT(*)
    FROM actos
    WHERE fecha BETWEEN :inicio AND :fin
      AND estado <> 'cancelado'
");
$actosMes->execute([
    'inicio' => $inicioMes,
    'fin' => $finMes,
]);
$totalActosMes = (int) $actosMes->fetchColumn();

$stmt = $pdo->prepare("
    SELECT a.titulo, a.fecha, a.hora, a.ubicacion, r.estado, oc.nombre AS opcion_nombre
    FROM reservas r
    INNER JOIN actos a ON a.id = r.acto_id
    LEFT JOIN opciones_comida oc ON oc.id = r.opcion_comida_id
    WHERE r.fallero_id = :fallero_id
      AND a.fecha BETWEEN :inicio AND :fin
      AND r.estado <> 'cancelada'
    ORDER BY a.fecha ASC, a.hora ASC
");
$stmt->execute([
    'fallero_id' => $falleroIdSesion,
    'inicio' => $inicioMes,
    'fin' => $finMes,
]);
$misActosMes = $stmt->fetchAll();

$stmt = $pdo->prepare("
    SELECT id, titulo, texto, created_at
    FROM avisos
    WHERE (visible_desde IS NULL OR visible_desde <= NOW())
      AND (visible_hasta IS NULL OR visible_hasta >= NOW())
    ORDER BY created_at DESC
    LIMIT 3
");
$stmt->execute();
$ultimosAvisos = $stmt->fetchAll();

$actosCalendario = $pdo->query("
    SELECT id, titulo, fecha, hora, tipo, estado
    FROM actos
    WHERE estado <> 'cancelado'
    ORDER BY fecha ASC, hora ASC
")->fetchAll(PDO::FETCH_ASSOC);

$eventos = [];

foreach ($actosCalendario as $acto) {
    $start = $acto['fecha'];

    if (!empty($acto['hora'])) {
        $start .= 'T' . substr($acto['hora'], 0, 5) . ':00';
    }

    $eventos[] = [
        'id' => $acto['id'],
        'title' => $acto['titulo'],
        'start' => $start,
        'url' => 'acto_detalle.php?id=' . $acto['id'],
        'className' => 'evento-' . $acto['tipo'],
        'extendedProps' => [
            'tipo' => $acto['tipo'],
            'estado' => $acto['estado'],
        ],
    ];
}
?>

<main class="main-dashboard">
    <header class="dashboard-topbar">
        <button class="mobile-menu-btn" type="button" onclick="toggleSidebar()">☰</button>

        <div>
            <h1>Mi panel</h1>
            <p>Hola<?= $falleroActual ? ', ' . e($falleroActual['nombre']) : '' ?>. Aquí tienes tu resumen de la falla.</p>
        </div>

        <div class="topbar-actions">
            <a href="logout.php" class="topbar-btn">➤ Salir</a>
        </div>
    </header>

    <section class="dashboard-content">
        <?php if (!$falleroActual): ?>
            <div class="alert alert-warning">Tu usuario no tiene una ficha de fallero asociada.</div>
        <?php endif; ?>

        <div class="user-dashboard-grid compact-user-dashboard">
            <div class="calendar-card user-calendar-card">
                <div class="panel-card-header">
                    <div>
                        <span class="eyebrow">Agenda fallera</span>
                        <h2>Calendario de actos</h2>
                    </div>
                    <a href="calendario.php" class="soft-link-btn">Ver completo</a>
                </div>

                <div id="calendar"></div>
            </div>

            <aside class="user-side-column">
                <div class="dashboard-panel side-summary-card">
                    <div class="summary-card-icon">✅</div>
                    <div>
                        <span class="eyebrow">Reservas</span>
                        <strong><?= count($misActosMes) ?></strong>
                        <small>actos a los que estás apuntado este mes</small>
                    </div>
                </div>

                <div class="dashboard-panel side-summary-card orange">
                    <div class="summary-card-icon">📅</div>
                    <div>
                        <span class="eyebrow">Agenda</span>
                        <strong><?= e((string) $totalActosMes) ?></strong>
                        <small>actos totales programados este mes</small>
                    </div>
                </div>

                <div class="card-modern dashboard-panel avisos-panel">
                    <div class="panel-card-header compact-header">
                        <div>
                            <span class="eyebrow">Comunicados</span>
                            <h2>Últimos avisos</h2>
                        </div>
                        <a href="mis_avisos.php" class="soft-link-btn">Ver todos</a>
                    </div>

                    <div class="avisos-list compact-list">
                        <?php foreach ($ultimosAvisos as $aviso): ?>
                            <a href="mis_avisos.php" class="aviso-item is-featured">
                                <div class="aviso-icon">📣</div>

                                <div class="aviso-content">
                                    <div class="aviso-title-row">
                                        <strong><?= e($aviso['titulo']) ?></strong>
                                    </div>

                                    <p>
                                        <?= e(strlen(strip_tags((string) $aviso['texto'])) > 75
                                            ? substr(strip_tags((string) $aviso['texto']), 0, 75) . '...'
                                            : strip_tags((string) $aviso['texto'])) ?>
                                    </p>

                                    <small>
                                        <?= e(date('d/m/Y H:i', strtotime($aviso['created_at']))) ?>
                                    </small>
                                </div>
                            </a>
                        <?php endforeach; ?>

                        <?php if (!$ultimosAvisos): ?>
                            <p class="empty-state mb-0">No hay avisos publicados todavía.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card-modern dashboard-panel actos-panel">
                    <div class="panel-card-header compact-header">
                        <div>
                            <span class="eyebrow">Este mes</span>
                            <h2>Mis actos</h2>
                        </div>
                        <a href="mis_reservas.php" class="soft-link-btn">Reservas</a>
                    </div>

                    <div class="pretty-list compact-list">
                        <?php foreach ($misActosMes as $acto): ?>
                            <div class="pretty-list-item">
                                <div class="pretty-dot">🎉</div>

                                <div>
                                    <strong><?= e($acto['titulo']) ?></strong>
                                    <small>
                                        <?= e(date('d/m/Y', strtotime($acto['fecha']))) ?>
                                        <?= e(substr((string) $acto['hora'], 0, 5)) ?>
                                        ·
                                        <?= e($acto['ubicacion'] ?: 'Sin ubicación') ?>
                                    </small>
                                    <span class="soft-badge success">
                                        Opción: <?= e($acto['opcion_nombre'] ?: 'Sin opción') ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <?php if (!$misActosMes): ?>
                            <p class="empty-state mb-0">Este mes todavía no te has apuntado a ningún acto.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card-modern dashboard-panel family-card">
                    <div class="panel-card-header compact-header">
                        <div>
                            <span class="eyebrow">Integrantes</span>
                            <h2>Mi Familia</h2>
                        </div>
                        <a href="mi_familia.php" class="soft-link-btn">Ver ficha</a>
                    </div>

                    <?php if ($falleroActual && $familiaId > 0): ?>
                        <div class="family-name-card">
                            <span>👨‍👩‍👧‍👦</span>
                            <div>
                                <strong><?= e($falleroActual['familia_nombre'] ?: 'Familia') ?></strong>
                                <small>
                                    <?= count($miembrosFamilia) ?>
                                    integrante<?= count($miembrosFamilia) === 1 ? '' : 's' ?>
                                </small>
                            </div>
                        </div>

                        <div class="family-members-list compact-list">
                            <?php foreach ($miembrosFamilia as $miembro): ?>
                                <div class="family-member-pill">
                                    <span class="member-avatar">
                                        <?= e(strtoupper(substr((string) $miembro['nombre'], 0, 1))) ?>
                                    </span>

                                    <div>
                                        <strong><?= e($miembro['nombre'] . ' ' . $miembro['apellidos']) ?></strong>
                                        <small><?= e($miembro['tipo']) ?> · <?= e($miembro['estado']) ?></small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="empty-state mb-0">No tienes una familia asociada.</p>
                    <?php endif; ?>
                </div>
            </aside>
        </div>
    </section>
</main>
<?php endif; ?>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/locales/es.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    const calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'es',
        initialView: 'dayGridMonth',
        firstDay: 1,
        height: <?= is_admin() ? '430' : '520' ?>,
        contentHeight: <?= is_admin() ? '365' : '455' ?>,
        dayMaxEventRows: 2,
        nowIndicator: true,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listMonth'
        },
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            list: 'Lista'
        },
        events: <?= json_encode($eventos ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        },
        eventClick: function(info) {
            if (info.event.url) {
                info.jsEvent.preventDefault();
                window.location.href = info.event.url;
            }
        }
    });

    calendar.render();
});
</script>

<?php include __DIR__ . '/footer.php'; ?>