<?php
require_once __DIR__ . '/config.php';
require_login();
$page_title = 'Avisos';

$avisos = $pdo->query("SELECT * FROM avisos ORDER BY created_at DESC")->fetchAll();

include __DIR__ . '/header.php';
include __DIR__ . '/sidebar.php';
?>

<main class="main-dashboard">
    <header class="dashboard-topbar">
        <button class="mobile-menu-btn" type="button" onclick="toggleSidebar()">☰</button>
        <div>
            <h1>Avisos</h1>
            <p>Comunicaciones de la falla</p>
        </div>
        <div class="topbar-actions">
            <a href="index.php" class="topbar-btn">← Panel</a>
            <a href="logout.php" class="topbar-btn">➤ Salir</a>
        </div>
    </header>

    <section class="dashboard-content">

<div class="row g-4">
    <?php foreach ($avisos as $aviso): ?>
        <div class="col-lg-6">
            <div class="card-modern aviso-destacado-card h-100">
                <h2 class="h5"><?= e($aviso['titulo']) ?></h2>
                <p><?= nl2br(e($aviso['texto'])) ?></p>
                <small class="text-muted"><?= e($aviso['created_at']) ?></small>
            </div>
        </div>
    <?php endforeach; ?>
</div>

</section>
</main>

<?php include __DIR__ . '/footer.php'; ?>
