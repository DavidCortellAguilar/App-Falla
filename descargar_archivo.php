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
$nombreDescarga = basename((string) $arch['nombre_original']);

header('Content-Type: ' . $mime);
header('Content-Disposition: attachment; filename="' . addslashes($nombreDescarga) . '"');
header('Content-Length: ' . filesize($ruta));
header('Cache-Control: private, no-cache');
readfile($ruta);
exit;
