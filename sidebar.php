<?php
$currentPage = basename($_SERVER['SCRIPT_NAME'] ?? 'index.php');
function menu_active(string $page, string $currentPage): string
{
    return $page === $currentPage ? ' active' : '';
}
?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <img src="./img/327113823_436363905290382_7275278403003823711_n.jpg" alt="Logo" class="sidebar-logo">

        <div>
            <div class="sidebar-title">Falla San Sebastián<br>Arzobispo Fuero</div>
            <?php if (is_admin()): ?>
                <div class="sidebar-subtitle">ADMINISTRACIÓN</div>
                <div class="sidebar-subtitle">PRESIDENCIA</div>
            <?php endif; ?>
        </div>
    </div>

    <nav class="sidebar-menu">
        <?php if (is_admin()): ?>
            <div class="menu-section">Principal</div>

            <a href="index.php" class="menu-link<?= menu_active('index.php', $currentPage) ?>">
                <span>🧭</span><span>Vista global</span>
            </a>

            <a href="avisos.php" class="menu-link<?= menu_active('avisos.php', $currentPage) ?>">
                <span>📣</span><span>Avisos</span>
            </a>

            <a href="calendario.php" class="menu-link<?= menu_active('calendario.php', $currentPage) ?>">
                <span>📅</span><span>Calendario</span>
            </a>

            <a href="actos.php" class="menu-link<?= menu_active('actos.php', $currentPage) ?>">
                <span>📅</span><span>Actos</span>
            </a>

            <a href="juntas.php" class="menu-link<?= in_array($currentPage, ['juntas.php','junta_detalle.php']) ? ' active' : '' ?>">
                <span>📋</span><span>Juntas</span>
            </a>

            <div class="menu-section">Faller@s</div>

            <a href="falleros.php" class="menu-link<?= menu_active('falleros.php', $currentPage) ?>">
                <span>👥</span><span>Censo</span>
            </a>

            <a href="familias.php" class="menu-link<?= menu_active('familias.php', $currentPage) ?>">
                <span>👨‍👩‍👧</span><span>Familias</span>
            </a>

            <a href="falleros_pendientes.php" class="menu-link<?= menu_active('falleros_pendientes.php', $currentPage) ?>">
                <span>🕘</span><span>Solicitudes pendientes</span>
            </a>

            <div class="menu-section">Reservas</div>

            <a href="reservas.php" class="menu-link<?= menu_active('reservas.php', $currentPage) ?>">
                <span>☰</span><span>Todas las reservas</span>
            </a>
            <a href="escanear_qr.php" class="menu-link<?= menu_active('escanear_qr.php', $currentPage) ?>">
                <span>📷</span><span>Escanear QR</span>
            </a>

            <div class="menu-section">Control</div>

            <a href="auditoria.php" class="menu-link<?= menu_active('auditoria.php', $currentPage) ?>">
                <span>🧾</span><span>Auditoría</span>
            </a>
        <?php else: ?>
            <div class="menu-section">Mi zona</div>

            <a href="index.php" class="menu-link<?= menu_active('index.php', $currentPage) ?>">
                <span>🧭</span><span>Vista general</span>
            </a>

            <a href="calendario.php" class="menu-link<?= menu_active('calendario.php', $currentPage) ?>">
                <span>📅</span><span>Calendario</span>
            </a>

            <a href="mis_actos.php" class="menu-link<?= menu_active('mis_actos.php', $currentPage) ?>">
                <span>📅</span><span>Actos</span>
            </a>

            <a href="mis_avisos.php" class="menu-link<?= menu_active('mis_avisos.php', $currentPage) ?>">
                <span>📣</span><span>Avisos</span>
            </a>

            <a href="mis_reservas.php" class="menu-link<?= menu_active('mis_reservas.php', $currentPage) ?>">
                <span>✅</span><span>Mis reservas</span>
            </a>

            <a href="mi_familia.php" class="menu-link<?= menu_active('mi_familia.php', $currentPage) ?>">
                <span>👨‍👩‍👧</span><span>Mi Familia</span>
            </a>

            <a href="perfil.php" class="menu-link<?= menu_active('perfil.php', $currentPage) ?>">
                <span>👤</span><span>Mi perfil</span>
            </a>
        <?php endif; ?>
    </nav>
</aside>

<div class="sidebar-backdrop" id="sidebarBackdrop"></div>
