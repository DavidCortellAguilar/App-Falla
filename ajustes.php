<?php
require_once __DIR__ . '/config.php';
require_login();
$page_title = 'Ajustes generales';
include __DIR__ . '/header.php';
include __DIR__ . '/sidebar.php';
?>
<main class="main-dashboard">
    <header class="dashboard-topbar">
        <button class="mobile-menu-btn" type="button" onclick="toggleSidebar()">☰</button>
        <div><h1>Ajustes generales</h1><p>Configuración de la aplicación</p></div>
        <div class="topbar-actions"><a href="index.php" class="topbar-btn">← Panel</a><a href="logout.php" class="topbar-btn">➤ Salir</a></div>
    </header>
    <section class="dashboard-content">
        <div class="card-modern">
            <h2 class="h5 mb-2">Ajustes generales</h2>
            <p class="text-muted mb-0">Aquí se podrán configurar parámetros generales.</p>
        </div>
    </section>
</main>
<?php include __DIR__ . '/footer.php'; ?>
