<?php require_once __DIR__ . '/config.php'; ?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title><?= e($page_title ?? 'Gestión Falla') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#111827">
    
    <link rel="icon" type="image/png" href="/img/logo.png">
    <link rel="apple-touch-icon" href="/img/icon-512.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/img/icon-512.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="app.css" rel="stylesheet">
    <?php if (defined('TURNSTILE_SITE_KEY') && turnstile_configured()): ?>
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <?php endif; ?>
</head>
<body>
