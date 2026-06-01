<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Seguridad general
|--------------------------------------------------------------------------
| Rellena estas claves desde Cloudflare Turnstile:
| https://dash.cloudflare.com/?to=/:account/turnstile
| También puedes definirlas como variables de entorno en el hosting:
| TURNSTILE_SITE_KEY y TURNSTILE_SECRET_KEY
*/
define('TURNSTILE_SITE_KEY', getenv('TURNSTILE_SITE_KEY') ?: '0x4AAAAAADN2O9emuqq0U21T');
define('TURNSTILE_SECRET_KEY', getenv('TURNSTILE_SECRET_KEY') ?: '0x4AAAAAADN2O8cI5SQ3SD-uZKEjgxHV8bI');

/*
|--------------------------------------------------------------------------
| Claves VAPID para notificaciones push
|--------------------------------------------------------------------------
| Puedes definirlas como variables de entorno en el hosting:
| VAPID_PUBLIC_KEY, VAPID_PRIVATE_KEY y VAPID_SUBJECT
*/
define('VAPID_PUBLIC_KEY',  getenv('VAPID_PUBLIC_KEY')  ?: 'BGFSDiBrq_74MbchkVkpdgci3pbg46BypMTuBytjSDpTqBmQNxJrcrKYEPm4qxspQVFJnX1myy8aurgQP3W_byE');
define('VAPID_PRIVATE_KEY', getenv('VAPID_PRIVATE_KEY') ?: 'YAwA9FBRU6W05CGFIhxtAp2ue8GbTCmyladIj_N0bq0');
define('VAPID_SUBJECT',     getenv('VAPID_SUBJECT')     ?: 'mailto:davidsonicx44@gmail.com');

$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name('FALLA_SESION');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');

$DB_HOST = 'localhost';
$DB_NAME = 'u287751603_falla';
$DB_USER = 'u287751603_falla';
$DB_PASS = 'Eloimiquel44';

try {
    $pdo = new PDO(
        "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('Error de conexión con la base de datos: ' . htmlspecialchars($e->getMessage()));
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function is_logged(): bool
{
    return !empty($_SESSION['user_id']);
}

function is_admin(): bool
{
    return ($_SESSION['role'] ?? '') === 'admin';
}

function require_login(): void
{
    if (!is_logged()) {
        header('Location: login.php');
        exit;
    }
}

function require_admin(): void
{
    require_login();

    if (!is_admin()) {
        http_response_code(403);
        exit('Acceso no autorizado.');
    }
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function check_csrf(): void
{
    $token = $_POST['csrf_token'] ?? '';

    if (!$token || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(419);
        exit('Token CSRF no válido.');
    }
}

function honeypot_field(): string
{
    return '<div aria-hidden="true" style="position:absolute;left:-10000px;top:auto;width:1px;height:1px;overflow:hidden;">'
        . '<label>Deja este campo vacío</label>'
        . '<input type="text" name="website" tabindex="-1" autocomplete="off">'
        . '</div>';
}

function check_honeypot(): void
{
    if (!empty($_POST['website'] ?? '')) {
        http_response_code(400);
        exit('Solicitud no válida.');
    }
}

function turnstile_configured(): bool
{
    return TURNSTILE_SITE_KEY !== 'PEGA_AQUI_TU_SITE_KEY'
        && TURNSTILE_SECRET_KEY !== 'PEGA_AQUI_TU_SECRET_KEY'
        && TURNSTILE_SITE_KEY !== ''
        && TURNSTILE_SECRET_KEY !== '';
}

function turnstile_widget(): string
{
    if (!turnstile_configured()) {
        return '<div class="alert alert-warning small mb-3">Cloudflare Turnstile está pendiente de configurar en <code>config.php</code>.</div>';
    }

    return '<div class="cf-turnstile mb-3" data-sitekey="' . e(TURNSTILE_SITE_KEY) . '"></div>';
}

function check_turnstile(): void
{
    if (!turnstile_configured()) {
        return;
    }

    $token = $_POST['cf-turnstile-response'] ?? '';
    if ($token === '') {
        http_response_code(400);
        exit('Verificación anti-bots obligatoria.');
    }

    $payload = http_build_query([
        'secret' => TURNSTILE_SECRET_KEY,
        'response' => $token,
        'remoteip' => $_SERVER['REMOTE_ADDR'] ?? null,
    ]);

    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => $payload,
            'timeout' => 8,
        ],
    ]);

    $response = @file_get_contents('https://challenges.cloudflare.com/turnstile/v0/siteverify', false, $context);
    $data = $response ? json_decode($response, true) : null;

    if (empty($data['success'])) {
        http_response_code(400);
        exit('No se ha podido validar la verificación anti-bots.');
    }
}

function log_activity(PDO $pdo, string $accion, string $modulo, string $descripcion = ''): void
{
    $stmt = $pdo->prepare("
        INSERT INTO activity_logs (user_id, accion, modulo, descripcion, ip_address, user_agent)
        VALUES (:user_id, :accion, :modulo, :descripcion, :ip, :ua)
    ");
    $stmt->execute([
        'user_id' => $_SESSION['user_id'] ?? null,
        'accion' => $accion,
        'modulo' => $modulo,
        'descripcion' => $descripcion,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
        'ua' => $_SERVER['HTTP_USER_AGENT'] ?? null,
    ]);
}

function redirect(string $url): void
{
    header("Location: {$url}");
    exit;
}
