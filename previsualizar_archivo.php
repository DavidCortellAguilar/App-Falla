<?php
require_once __DIR__ . '/config.php';
require_admin();
require_once __DIR__ . '/juntas_helper.php';
ensure_juntas_tables($pdo);

$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM junta_archivos WHERE id = :id LIMIT 1");
$stmt->execute(['id' => $id]);
$arch = $stmt->fetch();

if (!$arch) {
    http_response_code(404);
    exit('Archivo no encontrado.');
}

$rutaRelativa = str_replace(['..', '\\'], ['', '/'], (string) $arch['ruta']);
$ruta = __DIR__ . '/' . ltrim($rutaRelativa, '/');

if (!is_file($ruta)) {
    http_response_code(404);
    exit('El archivo no existe en el servidor. Vuelve a subirlo desde el detalle de la junta.');
}

$mime = mime_content_type($ruta) ?: 'application/octet-stream';
$nombre = basename((string) $arch['nombre_original']);
$ext = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));

$inlineTypes = [
    'pdf'  => 'application/pdf',
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png'  => 'image/png',
    'webp' => 'image/webp',
];

if (isset($inlineTypes[$ext])) {
    $mime = $inlineTypes[$ext];
    header('Content-Type: ' . $mime);
    header('Content-Disposition: inline; filename="' . addslashes($nombre) . '"');
    header('Content-Length: ' . filesize($ruta));
    header('Cache-Control: private, max-age=0, must-revalidate');
    readfile($ruta);
    exit;
}

$page_title = 'Previsualizar archivo';
include __DIR__ . '/header.php';
include __DIR__ . '/sidebar.php';
?>

<main class="main-dashboard">
    <header class="dashboard-topbar">
        <button class="mobile-menu-btn" type="button" onclick="toggleSidebar()">☰</button>
        <div>
            <h1>Previsualización no disponible</h1>
            <p><?= e($nombre) ?></p>
        </div>
        <div class="topbar-actions">
            <a href="descargar_archivo.php?id=<?= (int) $arch['id'] ?>" class="topbar-btn">⬇ Descargar</a>
            <a href="junta_detalle.php?id=<?= (int) $arch['junta_id'] ?>" class="topbar-btn">← Volver</a>
        </div>
    </header>

    <section class="dashboard-content">
        <div class="card-modern text-center py-5">
            <div style="font-size:52px;margin-bottom:16px;">📎</div>
            <h2 class="h5 mb-2">Este tipo de archivo no se puede previsualizar directamente en el navegador</h2>
            <p class="text-muted mb-4">Los archivos Word y Excel normalmente deben descargarse para abrirse correctamente.</p>
            <a href="descargar_archivo.php?id=<?= (int) $arch['id'] ?>" class="btn btn-primary">⬇ Descargar archivo</a>
        </div>
    </section>
</main>

<?php include __DIR__ . '/footer.php'; ?>
