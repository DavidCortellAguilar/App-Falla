<?php
require_once __DIR__ . '/config.php';

if (is_logged()) {
    redirect('index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    check_honeypot();

    $dni = trim($_POST['dni'] ?? '');

    $stmt = $pdo->prepare("
        SELECT *
        FROM users
        WHERE dni = :dni
          AND is_active = 1
        LIMIT 1
    ");
    $stmt->execute(['dni' => $dni]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $error = 'DNI no encontrado o pendiente de aprobación.';
    } else {
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['dni'] = $user['dni'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['fallero_id'] = $user['fallero_id'];

        $pdo->prepare("
            UPDATE users
            SET last_login = NOW(),
                failed_attempts = 0
            WHERE id = :id
        ")->execute([
            'id' => $user['id'],
        ]);

        log_activity($pdo, 'login', 'auth', 'Inicio de sesión correcto');

        redirect('index.php');
    }
}

$page_title = 'Acceso';
include __DIR__ . '/header.php';
?>

<main class="login-content">
<div class="card-modern login-card">
    <div class="text-center mb-4">
        <div class="brand-icon mx-auto mb-3">
            <img src="./img/327113823_436363905290382_7275278403003823711_n.jpg" alt="Logo">
        </div>

        <h1 class="h3 mb-1">Falla San Sebastián Arzobispo Fuero</h1>
        <p class="text-muted mb-0">Acceso privado para falleros</p>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger">
            <?= e($error) ?>
        </div>
    <?php endif; ?>

    <form method="post" autocomplete="off">
        <?= csrf_field() ?>
        <?= honeypot_field() ?>
        <div class="mb-3">
            <label class="form-label">DNI</label>
            <input
                class="form-control"
                name="dni"
                required
                autofocus
            >
        </div>


        <button class="btn btn-primary w-100 btn-lg" type="submit">
            Entrar
        </button>
    </form>

    
    
    
</div>
</main>

<?php include __DIR__ . '/footer.php'; ?>