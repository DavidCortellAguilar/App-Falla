<?php
require_once __DIR__ . '/config.php';
require_login();

$juntaId = (int) ($_GET['id'] ?? 0);

require_once __DIR__ . '/juntas_helper.php';
ensure_juntas_tables($pdo);

// ── POST: acciones de esta página ────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!is_admin()) {
        http_response_code(403);
        exit('Acceso no autorizado.');
    }

    check_csrf();

    // Eliminar un archivo concreto
    if (($_POST['action'] ?? '') === 'delete_archivo') {
        $archId  = (int) ($_POST['archivo_id'] ?? 0);
        $jIdPost = (int) ($_POST['junta_id']   ?? 0);

        $stmtA = $pdo->prepare("SELECT ruta FROM junta_archivos WHERE id = :id AND junta_id = :junta_id");
        $stmtA->execute(['id' => $archId, 'junta_id' => $jIdPost]);
        $arch = $stmtA->fetch();

        if ($arch) {
            $ruta = __DIR__ . '/' . $arch['ruta'];
            if (is_file($ruta)) unlink($ruta);
            $pdo->prepare("DELETE FROM junta_archivos WHERE id = :id")->execute(['id' => $archId]);
            log_activity($pdo, 'delete', 'junta_archivos', 'Archivo de junta eliminado ID ' . $archId);
            log_activity($pdo, 'create', 'junta_archivos', 'Archivo añadido a junta ID ' . $jIdPost);
        }

        redirect('junta_detalle.php?id=' . $jIdPost);
    }

    // Añadir documento a la junta
    if (($_POST['action'] ?? '') === 'add_archivo') {
        $jIdPost = (int) ($_POST['junta_id'] ?? 0);

        $rutaArchivo = subir_archivo_junta();
        if ($rutaArchivo !== null) {
            $pdo->prepare("
                INSERT INTO junta_archivos (junta_id, nombre_original, ruta, created_by)
                VALUES (:junta_id, :nombre_original, :ruta, :created_by)
            ")->execute([
                'junta_id'        => $jIdPost,
                'nombre_original' => $_FILES['archivo']['name'],
                'ruta'            => $rutaArchivo,
                'created_by'      => current_user_id(),
            ]);
        }

        redirect('junta_detalle.php?id=' . $jIdPost);
    }
}

// ── Cargar junta ─────────────────────────────────────────────────────────────
$stmt = $pdo->prepare("SELECT * FROM juntas WHERE id = :id LIMIT 1");
$stmt->execute(['id' => $juntaId]);
$junta = $stmt->fetch();

if (!$junta) {
    http_response_code(404);
    exit('Junta no encontrada.');
}

$page_title = e($junta['nombre']);

// Archivos de esta junta
$stmt = $pdo->prepare("
    SELECT ja.*,
           COALESCE(NULLIF(TRIM(CONCAT_WS(' ', f.nombre, f.apellidos)), ''), u.dni, 'Administrador') AS subido_por_nombre
    FROM junta_archivos ja
    LEFT JOIN users u ON u.id = ja.created_by
    LEFT JOIN falleros f ON f.id = u.fallero_id
    WHERE ja.junta_id = :junta_id
    ORDER BY ja.created_at ASC
");
$stmt->execute(['junta_id' => $juntaId]);
$archivos = $stmt->fetchAll();

// Helper: icono y etiqueta por extensión (compatible PHP 7.4+)
function archivo_info(string $ruta): array {
    $ext = strtolower(pathinfo($ruta, PATHINFO_EXTENSION));
    if ($ext === 'pdf') {
        return ['icon' => '📄', 'label' => 'PDF',   'color' => '#dc2626', 'bg' => 'rgba(220,38,38,.08)'];
    }
    if (in_array($ext, ['doc', 'docx', 'odt'], true)) {
        return ['icon' => '📝', 'label' => 'Word',  'color' => '#2563eb', 'bg' => 'rgba(37,99,235,.08)'];
    }
    if (in_array($ext, ['xls', 'xlsx', 'ods'], true)) {
        return ['icon' => '📊', 'label' => 'Excel', 'color' => '#16a34a', 'bg' => 'rgba(22,163,74,.08)'];
    }
    return ['icon' => '📎', 'label' => strtoupper($ext), 'color' => '#6b7280', 'bg' => 'rgba(107,114,128,.08)'];
}

// Helper: tamaño legible
function tam_legible(string $ruta): string {
    $fullPath = __DIR__ . '/' . $ruta;
    if (!is_file($fullPath)) return '';
    $bytes = filesize($fullPath);
    if ($bytes >= 1024 * 1024) return round($bytes / (1024 * 1024), 1) . ' MB';
    return round($bytes / 1024, 0) . ' KB';
}

include __DIR__ . '/header.php';
include __DIR__ . '/sidebar.php';
?>

<main class="main-dashboard">
    <header class="dashboard-topbar">
        <button class="mobile-menu-btn" type="button" onclick="toggleSidebar()">☰</button>
        <div>
            <h1><?= e($junta['nombre']) ?></h1>
            <p>Junta del <?= e(date('d/m/Y', strtotime($junta['fecha']))) ?></p>
        </div>
        <div class="topbar-actions">
            <a href="juntas.php" class="topbar-btn">← Juntas</a>
            <?php if (is_admin()): ?>
                <a href="juntas.php?edit=<?= (int) $junta['id'] ?>" class="topbar-btn">Editar</a>
            <?php endif; ?>
        </div>
    </header>

    <section class="dashboard-content">
        <div class="row g-4">

            <!-- Datos de la junta + formulario subir -->
            <div class="col-xl-4">
                <div class="card-modern sticky-form">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div style="width:54px;height:54px;border-radius:18px;background:rgba(99,102,241,.1);
                                    display:grid;place-items:center;font-size:26px;flex:0 0 auto;">
                            📋
                        </div>
                        <div>
                            <div class="fw-bold" style="font-size:18px;"><?= e($junta['nombre']) ?></div>
                            <div class="text-muted small">
                                <?= e(date('d/m/Y', strtotime($junta['fecha']))) ?>
                            </div>
                        </div>
                    </div>

                    <div class="detail-list">
                        <div>
                            <span>Fecha</span>
                            <strong><?= e(date('d/m/Y', strtotime($junta['fecha']))) ?></strong>
                        </div>
                        <div>
                            <span>Documentos</span>
                            <strong><?= count($archivos) ?> archivo<?= count($archivos) !== 1 ? 's' : '' ?></strong>
                        </div>
                        <?php if ($junta['descripcion']): ?>
                            <div>
                                <span>Descripción</span>
                                <strong><?= e($junta['descripcion']) ?></strong>
                            </div>
                        <?php endif; ?>
                        <div>
                            <span>Registrada el</span>
                            <strong><?= e(date('d/m/Y H:i', strtotime($junta['created_at']))) ?></strong>
                        </div>
                    </div>

                    <?php if (is_admin()): ?>
                    <hr class="my-4">
                    <h3 class="h6 mb-3">Añadir documento</h3>

                    <form method="post" enctype="multipart/form-data"
                          action="junta_detalle.php?id=<?= (int) $junta['id'] ?>">
                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                        <input type="hidden" name="action" value="add_archivo">
                        <input type="hidden" name="junta_id" value="<?= (int) $junta['id'] ?>">

                        <div class="mb-3">
                            <input class="form-control" type="file" name="archivo"
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.odt,.ods,.jpg,.jpeg,.png,.webp" required>
                            <div class="form-text">PDF, Word, Excel o imagen · máx. 20 MB</div>
                        </div>

                        <button class="btn btn-primary w-100">Subir documento</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Documentos -->
            <div class="col-xl-8">
                <h2 class="section-title mb-3">Documentos de la junta</h2>

                <?php if (!$archivos): ?>
                    <div class="card-modern text-muted text-center py-5">
                        Todavía no hay documentos en esta junta.<br>
Cuando el administrador suba documentos, aparecerán aquí para poder verlos y descargarlos.
                    </div>
                <?php endif; ?>

                <div class="d-grid gap-3">
                    <?php foreach ($archivos as $arch):
                        $info    = archivo_info($arch['ruta']);
                        $tamano  = tam_legible($arch['ruta']);
                        $nombre  = e($arch['nombre_original']);
                        $urlDesc = 'descargar_archivo.php?id=' . (int) $arch['id'];
                        $urlPrev = 'previsualizar_archivo.php?id=' . (int) $arch['id'];
                        $existeArchivo = archivo_junta_existe($arch['ruta']);
                    ?>
                        <div class="card-modern d-flex align-items-center gap-4"
                             style="padding:20px 24px;">

                            <!-- Icono tipo -->
                            <div style="width:60px;height:60px;border-radius:16px;
                                        background:<?= $info['bg'] ?>;
                                        display:grid;place-items:center;
                                        font-size:28px;flex:0 0 auto;">
                                <?= $info['icon'] ?>
                            </div>

                            <!-- Info -->
                            <div style="flex:1;min-width:0;">
                                <div class="fw-bold text-truncate" style="font-size:15px;">
                                    <?= $nombre ?>
                                </div>
                                <div class="d-flex flex-wrap gap-2 mt-1 align-items-center">
                                    <span class="badge"
                                          style="background:<?= $info['bg'] ?>;color:<?= $info['color'] ?>;
                                                 font-weight:800;border-radius:999px;padding:4px 10px;">
                                        <?= $info['label'] ?>
                                    </span>
                                    <?php if ($tamano): ?>
                                        <span class="text-muted small"><?= e($tamano) ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">No encontrado en servidor</span>
                                    <?php endif; ?>
                                    <span class="text-muted small">
                                        <?= e(date('d/m/Y H:i', strtotime($arch['created_at']))) ?>
                                        <?php if ($arch['subido_por_nombre']): ?>
                                            · <?= e($arch['subido_por_nombre']) ?>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Acciones -->
                            <div class="d-flex gap-2 align-items-center flex-shrink-0">
                                <?php if ($existeArchivo): ?>
                                    <a href="<?= $urlDesc ?>"
                                       class="btn btn-primary btn-sm"
                                       style="white-space:nowrap;">
                                        ⬇ Descargar
                                    </a>
                                    <a href="<?= $urlPrev ?>"
                                       target="_blank"
                                       class="btn btn-light btn-sm"
                                       style="white-space:nowrap;">
                                        👁 Previsualizar
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-sm" type="button" disabled>Archivo no disponible</button>
                                <?php endif; ?>

                                <?php if (is_admin()): ?>
                                <form method="post"
                                      action="junta_detalle.php?id=<?= (int) $junta['id'] ?>"
                                      onsubmit="return confirm('¿Eliminar este documento?')">
                                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                    <input type="hidden" name="action" value="delete_archivo">
                                    <input type="hidden" name="archivo_id" value="<?= (int) $arch['id'] ?>">
                                    <input type="hidden" name="junta_id" value="<?= (int) $junta['id'] ?>">
                                    <button class="btn btn-outline-danger btn-sm">✕</button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </section>
</main>

<?php include __DIR__ . '/footer.php'; ?>
