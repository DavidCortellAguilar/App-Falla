<?php
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: https://taronvibe.davidcortellaguilar.es');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

try {
    $stmt = $pdo->prepare("
        SELECT 
            id,
            titulo,
            descripcion,
            fecha,
            hora,
            ubicacion,
            imagen,
            tipo,
            estado,
            created_at
        FROM actos
        WHERE estado <> 'cancelado'
        ORDER BY fecha DESC, hora DESC, created_at DESC
    ");

    $stmt->execute();
    $actos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $baseUrl = 'https://fssaf.davidcortellaguilar.es/';

    $data = array_map(function ($acto) use ($baseUrl) {
        $imagen = $acto['imagen'] ?? '';

        if ($imagen !== '' && !preg_match('/^https?:\/\//', $imagen)) {
            $imagen = $baseUrl . ltrim($imagen, '/');
        }

        return [
            'id' => (int) $acto['id'],
            'titulo' => $acto['titulo'],
            'descripcion' => $acto['descripcion'],
            'fecha' => $acto['fecha'],
            'hora' => $acto['hora'] ? substr($acto['hora'], 0, 5) : null,
            'ubicacion' => $acto['ubicacion'],
            'imagen' => $imagen,
            'tipo' => $acto['tipo'],
            'estado' => $acto['estado'],
            'url' => $baseUrl . 'acto_detalle.php?id=' . (int) $acto['id'],
        ];
    }, $actos);

    echo json_encode([
        'success' => true,
        'total' => count($data),
        'actos' => $data,
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

} catch (Throwable $e) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Error al obtener los actos.',
    ], JSON_UNESCAPED_UNICODE);
}