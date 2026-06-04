<?php
require_once __DIR__ . '/config.php';
require_admin();
ensure_audit_columns($pdo);
$page_title = 'Avisos';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();

    if (($_POST['action'] ?? '') === 'delete') {
        $idDelete = (int) $_POST['id'];
        $stmt = $pdo->prepare("SELECT titulo FROM avisos WHERE id=:id");
        $stmt->execute(['id' => $idDelete]);
        $avisoDelete = $stmt->fetch();
        $avisoTituloDelete = $avisoDelete['titulo'] ?? ('ID ' . $idDelete);

        $pdo->prepare("DELETE FROM avisos WHERE id=:id")->execute(['id' => $idDelete]);
        log_activity($pdo, 'Eliminado', 'avisos', 'Aviso eliminado: ' . $avisoTituloDelete, $idDelete, $avisoTituloDelete);
        redirect('avisos.php');
    }

    $id = (int) ($_POST['id'] ?? 0);
    $esNuevoAviso = $id === 0;

    $data = [
        'titulo' => trim($_POST['titulo'] ?? ''),
        'texto' => trim($_POST['texto'] ?? ''),
        // Todos los avisos se guardan internamente como destacados y sin prioridad visible.
        'prioridad' => 'normal',
        'destacado' => 1,
    ];

    if ($id) {
        $data['id'] = $id;
        $data['updated_by'] = current_user_id();
        $pdo->prepare("
            UPDATE avisos 
            SET titulo=:titulo, 
                texto=:texto, 
                prioridad=:prioridad, 
                destacado=:destacado, 
                updated_at=NOW(),
                updated_by=:updated_by
            WHERE id=:id
        ")->execute($data);
    } else {
        $data['created_by'] = current_user_id();
        $pdo->prepare("
            INSERT INTO avisos 
            (titulo, texto, prioridad, destacado, created_by) 
            VALUES 
            (:titulo, :texto, :prioridad, :destacado, :created_by)
        ")->execute($data);
    }

    $avisoId = $id ?: (int) $pdo->lastInsertId();
    log_activity(
        $pdo,
        $esNuevoAviso ? 'Creado' : 'Modificado',
        'avisos',
        ($esNuevoAviso ? 'Aviso creado: ' : 'Aviso modificado: ') . $data['titulo'],
        $avisoId,
        $data['titulo']
    );

    if ($esNuevoAviso) {
        require_once __DIR__ . '/enviar-notificacion.php';

        enviarNotificacionPush(
            'Nuevo aviso de la falla',
            'Se ha publicado un nuevo aviso: ' . $data['titulo'],
            '/mis_avisos.php'
        );
    }

    redirect('avisos.php');
}

$edit = null;
if (!empty($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM avisos WHERE id=:id");
    $stmt->execute(['id' => (int) $_GET['edit']]);
    $edit = $stmt->fetch();
}

$avisos = $pdo->query("
    SELECT a.*,
           COALESCE(NULLIF(TRIM(CONCAT_WS(' ', cu_f.nombre, cu_f.apellidos)), ''), cu.dni, 'Sistema') AS creado_por_nombre,
           COALESCE(NULLIF(TRIM(CONCAT_WS(' ', uu_f.nombre, uu_f.apellidos)), ''), uu.dni, 'Sin editar') AS editado_por_nombre
    FROM avisos a
    LEFT JOIN users cu ON cu.id = a.created_by
    LEFT JOIN falleros cu_f ON cu_f.id = cu.fallero_id
    LEFT JOIN users uu ON uu.id = a.updated_by
    LEFT JOIN falleros uu_f ON uu_f.id = uu.fallero_id
    ORDER BY a.created_at DESC
")->fetchAll();

include __DIR__ . '/header.php';
include __DIR__ . '/sidebar.php';
?>

<main class="main-dashboard">
    <header class="dashboard-topbar">
        <button class="mobile-menu-btn" type="button" onclick="toggleSidebar()">☰</button>
        <div>
            <h1>Avisos</h1>
            <p>Tablón de anuncios y comunicaciones</p>
        </div>
        <div class="topbar-actions">
            <a href="index.php" class="topbar-btn">← Panel</a>
            <a href="logout.php" class="topbar-btn">➤ Salir</a>
        </div>
    </header>

    <section class="dashboard-content">

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card-modern">
            <h2 class="h5 mb-3"><?= $edit ? 'Editar aviso' : 'Nuevo aviso' ?></h2>
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="id" value="<?= e($edit['id'] ?? '') ?>">

                <div class="mb-3">
                    <label class="form-label">Título</label>
                    <input class="form-control" name="titulo" value="<?= e($edit['titulo'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Texto</label>
                    <textarea class="form-control" name="texto" rows="5" required><?= e($edit['texto'] ?? '') ?></textarea>
                </div>

                <button class="btn btn-primary w-100">Guardar</button>
            </form>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card-modern">
            <?php foreach ($avisos as $aviso): ?>
                <div class="aviso-admin-item border-bottom pb-3 mb-3">
                    <div class="d-flex gap-2 justify-content-between">
                        <div>
                            <h3 class="h6 mb-1"><?= e($aviso['titulo']) ?></h3>
                        </div>

                        <div>
                            <a class="btn btn-sm btn-light" href="avisos.php?edit=<?= $aviso['id'] ?>">Editar</a>

                            <form method="post" class="d-inline" onsubmit="return confirm('¿Eliminar aviso?')">
                                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $aviso['id'] ?>">
                                <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                            </form>
                        </div>
                    </div>

                    <p class="mt-2 mb-0"><?= nl2br(e($aviso['texto'])) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

</section>
</main>

<?php include __DIR__ . '/footer.php'; ?>