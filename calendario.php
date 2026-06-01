<?php
require_once __DIR__ . '/config.php';
require_login();

$page_title = 'Calendario';

$actos = $pdo->query("
    SELECT id, titulo, fecha, hora, tipo, estado
    FROM actos
    ORDER BY fecha ASC, hora ASC
")->fetchAll(PDO::FETCH_ASSOC);

$eventos = [];

foreach ($actos as $acto) {
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

include __DIR__ . '/header.php';
include __DIR__ . '/sidebar.php';
?>

<main class="main-dashboard">
    <header class="dashboard-topbar">
        <button class="mobile-menu-btn" type="button" onclick="toggleSidebar()">☰</button>

        <div>
            <h1>Calendario</h1>
            <p>Actos y reservas de la falla</p>
        </div>

        <div class="topbar-actions">
            <a href="index.php" class="topbar-btn">← Panel</a>
            <a href="logout.php" class="topbar-btn">➤ Salir</a>
        </div>
    </header>

    <section class="dashboard-content">
        <div class="calendar-card">
            <div id="calendar"></div>
        </div>
    </section>
</main>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/locales/es.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'es',
        initialView: 'dayGridMonth',
        firstDay: 1,
        height: 'auto',
        nowIndicator: true,

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listMonth'
        },

        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            list: 'Lista'
        },

        events: <?= json_encode($eventos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,

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