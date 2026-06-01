<?php
require_once 'config.php';
require_once 'vendor/autoload.php';

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

function enviarNotificacionPush(string $titulo, string $mensaje, string $url = '/', ?int $userId = null): void {
    global $pdo;

    // Construir URL absoluta si se pasa una ruta relativa
    if (str_starts_with($url, '/')) {
        $scheme   = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host     = $_SERVER['HTTP_HOST'] ?? '';
        $url      = $scheme . '://' . $host . $url;
    }

    $auth = [
        'VAPID' => [
            'subject'    => VAPID_SUBJECT,
            'publicKey'  => VAPID_PUBLIC_KEY,
            'privateKey' => VAPID_PRIVATE_KEY,
        ],
    ];

    $webPush = new WebPush($auth);

    if ($userId !== null) {
        $stmt = $pdo->prepare("SELECT * FROM push_subscriptions WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
    } else {
        $stmt = $pdo->query("SELECT * FROM push_subscriptions");
    }

    $subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$subscriptions) {
        return;
    }

    $payload = json_encode([
        'title' => $titulo,
        'body'  => $mensaje,
        'url'   => $url,
    ]);

    foreach ($subscriptions as $sub) {
        $subscription = Subscription::create([
            'endpoint'  => $sub['endpoint'],
            'publicKey' => $sub['p256dh'],
            'authToken' => $sub['auth'],
        ]);

        $webPush->queueNotification($subscription, $payload);
    }

    // Procesar envíos y eliminar suscripciones caducadas o inválidas
    foreach ($webPush->flush() as $report) {
        if (!$report->isSuccess()) {
            $pdo->prepare("DELETE FROM push_subscriptions WHERE endpoint = ?")
                ->execute([$report->getEndpoint()]);
        }
    }
}