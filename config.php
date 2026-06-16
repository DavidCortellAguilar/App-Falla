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
$DB_NAME = 'u104777796_falla';
$DB_USER = 'u104777796_falla';
$DB_PASS = 'SanSebastian26';

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


function current_user_id(): ?int
{
    return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
}

function audit_user_sql(string $alias): string
{
    return "COALESCE(NULLIF(TRIM(CONCAT_WS(' ', {$alias}f.nombre, {$alias}f.apellidos)), ''), {$alias}u.dni, 'Sistema')";
}

function audit_html(?string $createdName, ?string $createdAt, ?string $updatedName = null, ?string $updatedAt = null): string
{
    $html = '<div class="small text-muted mt-2">';
    if ($createdName || $createdAt) {
        $html .= 'Creado por <strong>' . e($createdName ?: 'Sistema') . '</strong>';
        if ($createdAt) {
            $html .= ' · ' . e(date('d/m/Y H:i', strtotime($createdAt)));
        }
    }
    if ($updatedName || $updatedAt) {
        $html .= '<br>Editado por <strong>' . e($updatedName ?: 'Sistema') . '</strong>';
        if ($updatedAt) {
            $html .= ' · ' . e(date('d/m/Y H:i', strtotime($updatedAt)));
        }
    }
    $html .= '</div>';
    return $html;
}

function ensure_audit_columns(PDO $pdo): void
{
    static $done = false;
    if ($done) return;
    $done = true;

    $statements = [
        "ALTER TABLE actos ADD COLUMN IF NOT EXISTS updated_by INT(11) DEFAULT NULL AFTER updated_at",
        "ALTER TABLE avisos ADD COLUMN IF NOT EXISTS updated_by INT(11) DEFAULT NULL AFTER updated_at",
        "ALTER TABLE juntas ADD COLUMN IF NOT EXISTS updated_by INT(11) DEFAULT NULL AFTER updated_at",
        "ALTER TABLE falleros ADD COLUMN IF NOT EXISTS created_by INT(11) DEFAULT NULL AFTER familia_id",
        "ALTER TABLE falleros ADD COLUMN IF NOT EXISTS updated_by INT(11) DEFAULT NULL AFTER updated_at",
        "ALTER TABLE familias ADD COLUMN IF NOT EXISTS created_by INT(11) DEFAULT NULL AFTER observaciones",
        "ALTER TABLE familias ADD COLUMN IF NOT EXISTS updated_by INT(11) DEFAULT NULL AFTER updated_at",
        "ALTER TABLE reservas ADD COLUMN IF NOT EXISTS created_by INT(11) DEFAULT NULL AFTER observaciones",
        "ALTER TABLE reservas ADD COLUMN IF NOT EXISTS updated_by INT(11) DEFAULT NULL AFTER updated_at",
        "ALTER TABLE activity_logs ADD COLUMN IF NOT EXISTS registro_id INT(11) DEFAULT NULL AFTER modulo",
        "ALTER TABLE activity_logs ADD COLUMN IF NOT EXISTS registro_nombre VARCHAR(255) DEFAULT NULL AFTER registro_id",
    ];

    foreach ($statements as $sql) {
        try { $pdo->exec($sql); } catch (Throwable $e) { /* Si el hosting no permite ALTER automático, usar audit_migration.sql */ }
    }
}

function log_activity(PDO $pdo, string $accion, string $modulo, string $descripcion = '', ?int $registroId = null, ?string $registroNombre = null): void
{
    // La auditoría solo debe guardar acciones realizadas por administradores.
    // No registramos accesos, perfil ni acciones de falleros/usuarios normales.
    $modulosIgnorados = ['auth', 'perfil'];
    if (!is_admin() || in_array($modulo, $modulosIgnorados, true)) {
        return;
    }

    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
        return;
    }

    // Comprobación adicional contra base de datos por seguridad.
    $check = $pdo->prepare("SELECT role FROM users WHERE id = :id AND is_active = 1 LIMIT 1");
    $check->execute(['id' => $userId]);
    if ($check->fetchColumn() !== 'admin') {
        return;
    }

    $accion = audit_action_label($accion);

    $stmt = $pdo->prepare("
        INSERT INTO activity_logs (user_id, accion, modulo, registro_id, registro_nombre, descripcion)
        VALUES (:user_id, :accion, :modulo, :registro_id, :registro_nombre, :descripcion)
    ");
    $stmt->execute([
        'user_id' => $userId,
        'accion' => $accion,
        'modulo' => $modulo,
        'registro_id' => $registroId,
        'registro_nombre' => $registroNombre !== null ? mb_substr($registroNombre, 0, 255) : null,
        'descripcion' => $descripcion,
    ]);
}

function audit_action_label(string $accion): string
{
    $accion = trim($accion);
    $map = [
        'create' => 'Creado',
        'created' => 'Creado',
        'crear' => 'Creado',
        'update' => 'Modificado',
        'updated' => 'Modificado',
        'edit' => 'Modificado',
        'editar' => 'Modificado',
        'save' => 'Modificado',
        'guardar' => 'Modificado',
        'delete' => 'Eliminado',
        'deleted' => 'Eliminado',
        'eliminar' => 'Eliminado',
        'approve' => 'Aprobado',
        'reject' => 'Rechazado',
        'cancel' => 'Cancelado',
        'pago' => 'Pago',
    ];

    $key = strtolower($accion);
    return $map[$key] ?? ucfirst($accion);
}

function redirect(string $url): void
{
    header("Location: {$url}");
    exit;
}
