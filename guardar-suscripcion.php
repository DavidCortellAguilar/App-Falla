<?php
require_once 'config.php';
require_login();

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['endpoint'], $data['keys']['p256dh'], $data['keys']['auth'])) {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
    exit;
}

$user_id = $_SESSION['user_id'];
$endpoint = $data['endpoint'];
$p256dh = $data['keys']['p256dh'];
$auth = $data['keys']['auth'];

// Evitar duplicados
$stmt = $pdo->prepare("SELECT id FROM push_subscriptions WHERE endpoint = ?");
$stmt->execute([$endpoint]);
$existing = $stmt->fetch();

if ($existing) {
    $stmt = $pdo->prepare("
        UPDATE push_subscriptions 
        SET user_id = ?, p256dh = ?, auth = ?
        WHERE endpoint = ?
    ");
    $stmt->execute([$user_id, $p256dh, $auth, $endpoint]);
} else {
    $stmt = $pdo->prepare("
        INSERT INTO push_subscriptions 
        (user_id, endpoint, p256dh, auth) 
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$user_id, $endpoint, $p256dh, $auth]);
}

echo json_encode(['success' => true]);