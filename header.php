<?php require_once __DIR__ . '/config.php'; ?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">

    <title><?= e($page_title ?? 'Área Fallera | Falla San Sebastián Arzobispo Fuero') ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="Área privada de la Falla San Sebastián Arzobispo Fuero. Consulta actos, reservas, avisos, juntas, notificaciones y toda la información de la comisión fallera.">

    <meta name="keywords" content="área fallera, gestión falla, Falla San Sebastián Arzobispo Fuero, falleros, reservas actos, avisos falla, juntas falla, comisión fallera Godella">

    <meta name="author" content="Falla San Sebastián Arzobispo Fuero">

    <link rel="canonical" href="https://app.fssaf.es/">

    <!-- Open Graph -->
    <meta property="og:title" content="Área Fallera | Falla San Sebastián Arzobispo Fuero">
    <meta property="og:description" content="Accede al área privada de la comisión para gestionar actos, reservas, avisos y notificaciones.">
    <meta property="og:image" content="https://app.fssaf.es/img/icon-512.png">
    <meta property="og:url" content="https://app.fssaf.es/">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Falla San Sebastián Arzobispo Fuero">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Área Fallera | Falla San Sebastián Arzobispo Fuero">
    <meta name="twitter:description" content="Aplicación oficial de la Falla San Sebastián Arzobispo Fuero.">
    <meta name="twitter:image" content="https://app.fssaf.es/img/icon-512.png">

    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#111827">

    <!-- iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="FSSAF">

    <!-- Iconos -->
    <link rel="icon" type="image/png" href="/img/logo.png?v=4">
    <link rel="apple-touch-icon" href="/img/icon-512.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/img/icon-512.png">

    <!-- Rendimiento -->
    <link rel="preload" as="image" href="/img/icon-512.png">
    <link rel="preconnect" href="https://cdn.jsdelivr.net">

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="app.css?v=<?= filemtime(__DIR__ . '/app.css') ?>" rel="stylesheet">

    
</head>
<body>