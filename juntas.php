<?php
require_once __DIR__ . '/config.php';
require_login();
if (is_admin()) {
    ensure_audit_columns($pdo);
}

$page_title = 'Juntas';

require_once __DIR__ . '/juntas_helper.php';
ensure_juntas_tables($pdo);

// ── POST ─────────────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!is_admin()) {
        http_response_code(403);
        exit('Acceso no autorizado.');
    }

    check_csrf();

    // Eliminar junta completa
    if (($_POST['action'] ?? '') === 'delete') {
        $idDel = (int) $_POST['id'];

        $stmtJunta = $pdo->prepare("SELECT nombre FROM juntas WHERE id = :id");
        $stmtJunta->execute(['id' => $idDel]);
        $juntaDelete = $stmtJunta->fetch();
        $juntaNombreDelete = $juntaDelete['nombre'] ?? ('ID ' . $idDel);

        $stmtArch = $pdo->prepare("SELECT ruta FROM junta_archivos WHERE junta_id = :id");
        $stmtArch->execute(['id' => $idDel]);
        foreach ($stmtArch->fetchAll() as $arch) {
            $ruta = __DIR__ . '/' . $arch['ruta'];
            if (is_file($ruta)) unlink($ruta);
        }

        $pdo->prepare("DELETE FROM junta_archivos WHERE junta_id = :id")->execute(['id' => $idDel]);
        $pdo->prepare("DELETE FROM juntas WHERE id = :id")->execute(['id' => $idDel]);
        log_activity($pdo, 'Eliminado', 'juntas', 'Junta eliminada: ' . $juntaNombreDelete, $idDel, $juntaNombreDelete);
        redirect('juntas.php');
    }

    // Crear / editar junta
    $id      = (int) ($_POST['id'] ?? 0);
    $esNuevaJunta = $id === 0;

    $data = [
        'nombre'      => trim($_POST['nombre'] ?? ''),
        'fecha'       => $_POST['fecha'] ?? date('Y-m-d'),
        'descripcion' => trim($_POST['descripcion'] ?? ''),
    ];

    if ($data['nombre'] === '') {
        redirect('juntas.php');
    }

    if ($id) {
        $data['id'] = $id;
        $data['updated_by'] = current_user_id();
        $pdo->prepare("
            UPDATE juntas
            SET nombre      = :nombre,
                fecha       = :fecha,
                descripcion = :descripcion,
                updated_at  = NOW(),
                updated_by  = :updated_by
            WHERE id = :id
        ")->execute($data);

        $juntaId = $id;
    } else {
        $data['created_by'] = current_user_id();
        $pdo->prepare("
            INSERT INTO juntas (nombre, fecha, descripcion, created_by)
            VALUES (:nombre, :fecha, :descripcion, :created_by)
        ")->execute($data);

        $juntaId = (int) $pdo->lastInsertId();
    }

    // Subir archivo si viene uno
    $rutaArchivo = subir_archivo_junta();
    if ($rutaArchivo !== null) {
        $pdo->prepare("
            INSERT INTO junta_archivos (junta_id, nombre_original, ruta, created_by)
            VALUES (:junta_id, :nombre_original, :ruta, :created_by)
        ")->execute([
            'junta_id'        => $juntaId,
            'nombre_original' => $_FILES['archivo']['name'],
            'ruta'            => $rutaArchivo,
            'created_by'      => current_user_id(),
        ]);
    }

    log_activity(
        $pdo,
        $esNuevaJunta ? 'Creado' : 'Modificado',
        'juntas',
        ($esNuevaJunta ? 'Junta creada: ' : 'Junta modificada: ') . $data['nombre'],
        $juntaId,
        $data['nombre']
    );
    redirect('junta_detalle.php?id=' . $juntaId);
}

// ── GET: formulario de edición ───────────────────────────────────────────────
$edit = null;
if (is_admin() && !empty($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM juntas WHERE id = :id");
    $stmt->execute(['id' => (int) $_GET['edit']]);
    $edit = $stmt->fetch();
}

// ── Listado ──────────────────────────────────────────────────────────────────
$juntas = $pdo->query("
    SELECT j.*,
           COALESCE(NULLIF(TRIM(CONCAT_WS(' ', cu_f.nombre, cu_f.apellidos)), ''), cu.dni, 'Sistema') AS creado_por_nombre,
           COALESCE(NULLIF(TRIM(CONCAT_WS(' ', uu_f.nombre, uu_f.apellidos)), ''), uu.dni, 'Sin editar') AS editado_por_nombre,
           COALESCE(a.total_archivos, 0) AS total_archivos
    FROM juntas j
    LEFT JOIN (
        SELECT junta_id, COUNT(*) AS total_archivos
        FROM junta_archivos
        GROUP BY junta_id
    ) a ON a.junta_id = j.id
    LEFT JOIN users cu ON cu.id = j.created_by
    LEFT JOIN falleros cu_f ON cu_f.id = cu.fallero_id
    LEFT JOIN users uu ON uu.id = j.updated_by
    LEFT JOIN falleros uu_f ON uu_f.id = uu.fallero_id
    ORDER BY j.fecha DESC, j.created_at DESC
")->fetchAll();

include __DIR__ . '/header.php';
include __DIR__ . '/sidebar.php';
?>

<main class="main-dashboard">
    <header class="dashboard-topbar">
        <button class="mobile-menu-btn" type="button" onclick="toggleSidebar()">☰</button>
        <div>
            <h1>Juntas</h1>
            <p>Registro de juntas y documentación asociada</p>
        </div>
        <div class="topbar-actions">
            <a href="index.php" class="topbar-btn">← Panel</a>
            <a href="logout.php" class="topbar-btn">➤ Salir</a>
        </div>
    </header>

    <section class="dashboard-content">
        <div class="row g-4">

            <?php if (is_admin()): ?>
            <!-- Formulario -->
            <div class="col-xl-4">
                <div class="card-modern sticky-form">
                    <h2 class="h5 mb-3"><?= $edit ? 'Editar junta' : 'Nueva junta' ?></h2>

                    <form method="post" enctype="multipart/form-data" action="juntas.php">
                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                        <input type="hidden" name="id" value="<?= e($edit['id'] ?? '') ?>">

                        <div class="mb-3">
                            <label class="form-label">Nombre de la junta</label>
                            <input class="form-control" name="nombre"
                                   value="<?= e($edit['nombre'] ?? '') ?>"
                                   placeholder="Ej. Junta ordinaria marzo 2025" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Fecha de celebración</label>
                            <input class="form-control" type="date" name="fecha"
                                   value="<?= e($edit['fecha'] ?? date('Y-m-d')) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descripción <span class="text-muted">(opcional)</span></label>
                            <textarea class="form-control" name="descripcion" rows="3"
                                      placeholder="Resumen breve de los temas tratados..."><?= e($edit['descripcion'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Adjuntar documento <span class="text-muted">(opcional)</span></label>
                            <input class="form-control" type="file" name="archivo"
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.odt,.ods,.jpg,.jpeg,.png,.webp">
                            <div class="form-text">PDF, Word, Excel o imagen · máx. 20 MB</div>
                        </div>

                        <button class="btn btn-primary w-100">
                            <?= $edit ? 'Guardar cambios' : 'Crear junta' ?>
                        </button>

                        <?php if ($edit): ?>
                            <a class="btn btn-light w-100 mt-2" href="juntas.php">Cancelar edición</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <?php endif; ?>

            <!-- Listado -->
            <div class="<?= is_admin() ? 'col-xl-8' : 'col-12' ?>">
                <h2 class="section-title mb-3">Juntas registradas</h2>

                <div class="actos-grid">
                    <?php foreach ($juntas as $junta): ?>
                        <article class="acto-card">
                            <a class="acto-card-main" href="junta_detalle.php?id=<?= (int) $junta['id'] ?>">
                                <div class="acto-card-badge" style="background:rgba(99,102,241,.1);color:#4f46e5;">
                                    📋 Junta
                                </div>
                                <h3><?= e($junta['nombre']) ?></h3>
                                <p><?= e(date('d/m/Y', strtotime($junta['fecha']))) ?></p>

                                <?php if ($junta['descripcion']): ?>
                                    <p class="text-muted small" style="margin-top:-10px;">
                                        <?= e(mb_substr($junta['descripcion'], 0, 100)) ?><?= mb_strlen($junta['descripcion']) > 100 ? '…' : '' ?>
                                    </p>
                                <?php endif; ?>

                                <div class="acto-card-meta">
                                    <span>📎 <?= (int) $junta['total_archivos'] ?> documento<?= (int) $junta['total_archivos'] !== 1 ? 's' : '' ?></span>
                                    <span>🗓 <?= e(date('d/m/Y', strtotime($junta['created_at']))) ?></span>
                                </div>

                                    <div class="acto-card-link">Ver documentos →</div>
                            </a>

                            <?php if (is_admin()): ?>
                            <div class="acto-card-actions">
                                <a class="btn btn-sm btn-light"
                                   href="juntas.php?edit=<?= (int) $junta['id'] ?>">Editar</a>

                                <form method="post" action="juntas.php"
                                      onsubmit="return confirm('¿Eliminar esta junta y todos sus archivos?')">
                                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= (int) $junta['id'] ?>">
                                    <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </form>
                            </div>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>

                    <?php if (!$juntas): ?>
                        <div class="card-modern text-center text-muted py-5" style="grid-column:1/-1;">
                            Todavía no hay juntas registradas.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </section>
</main>

<?php include __DIR__ . '/footer.php'; ?>
