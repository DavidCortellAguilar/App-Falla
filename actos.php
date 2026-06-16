<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/comidas_helpers.php';
ensure_comidas_multiples_schema($pdo);
require_admin();
ensure_audit_columns($pdo);

$page_title = 'Actos';

function subir_imagen_acto(?string $imagenActual = null): ?string {
    if (empty($_FILES['imagen']['name']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
        return $imagenActual;
    }

    $uploadDir = __DIR__ . '/uploads/actos/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }

    $permitidos = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
    ];

    $mime = mime_content_type($_FILES['imagen']['tmp_name']);

    if (!isset($permitidos[$mime])) {
        die('Formato de imagen no permitido. Usa JPG, PNG o WEBP.');
    }

    if ($_FILES['imagen']['size'] > 3 * 1024 * 1024) {
        die('La imagen no puede pesar más de 3MB.');
    }

    $extension = $permitidos[$mime];
    $nombreArchivo = 'acto_' . time() . '_' . bin2hex(random_bytes(6)) . '.' . $extension;

    $rutaFisica = $uploadDir . $nombreArchivo;
    $rutaWeb = 'uploads/actos/' . $nombreArchivo;

    if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaFisica)) {
        die('Error al subir la imagen.');
    }

    return $rutaWeb;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();

    if (($_POST['action'] ?? '') === 'delete') {
        $idDelete = (int) $_POST['id'];

        $stmt = $pdo->prepare("SELECT titulo, imagen FROM actos WHERE id=:id");
        $stmt->execute(['id' => $idDelete]);
        $actoDelete = $stmt->fetch();
        $actoTituloDelete = $actoDelete['titulo'] ?? ('ID ' . $idDelete);

        if (!empty($actoDelete['imagen'])) {
            $rutaImagen = __DIR__ . '/' . $actoDelete['imagen'];
            if (is_file($rutaImagen)) {
                unlink($rutaImagen);
            }
        }

        $pdo->prepare("DELETE FROM actos WHERE id=:id")->execute(['id' => $idDelete]);
        log_activity($pdo, 'Eliminado', 'actos', 'Acto eliminado: ' . $actoTituloDelete, $idDelete, $actoTituloDelete);
        redirect('actos.php');
    }

    $id = (int) ($_POST['id'] ?? 0);
    $esNuevoActo = $id === 0;

    $imagenActual = null;

    if ($id) {
        $stmt = $pdo->prepare("SELECT imagen FROM actos WHERE id=:id");
        $stmt->execute(['id' => $id]);
        $actoActual = $stmt->fetch();
        $imagenActual = $actoActual['imagen'] ?? null;
    }

    $imagenRuta = subir_imagen_acto($imagenActual);

    $data = [
        'titulo' => trim($_POST['titulo'] ?? ''),
        'descripcion' => trim($_POST['descripcion'] ?? ''),
        'fecha' => $_POST['fecha'] ?? date('Y-m-d'),
        'hora' => $_POST['hora'] ?: null,
        'ubicacion' => trim($_POST['ubicacion'] ?? ''),
        'imagen' => $imagenRuta,
        'tipo' => $_POST['tipo'] ?: 'Especial',
        'max_plazas' => $_POST['max_plazas'] !== '' ? (int) $_POST['max_plazas'] : null,
        'estado' => $_POST['estado'] ?? 'Abierto',
    ];

    if ($data['titulo'] === '') {
        redirect('actos.php');
    }

    if ($id) {
        $data['id'] = $id;
        $data['updated_by'] = current_user_id();

        $pdo->prepare("
            UPDATE actos
            SET titulo=:titulo,
                descripcion=:descripcion,
                fecha=:fecha,
                hora=:hora,
                ubicacion=:ubicacion,
                imagen=:imagen,
                tipo=:tipo,
                max_plazas=:max_plazas,
                estado=:estado,
                updated_at=NOW(),
                updated_by=:updated_by
            WHERE id=:id
        ")->execute($data);

        $actoId = $id;
    } else {
        $data['created_by'] = current_user_id();

        $pdo->prepare("
            INSERT INTO actos 
                (titulo, descripcion, fecha, hora, ubicacion, imagen, tipo, max_plazas, estado, created_by)
            VALUES 
                (:titulo, :descripcion, :fecha, :hora, :ubicacion, :imagen, :tipo, :max_plazas, :estado, :created_by)
        ")->execute($data);

        $actoId = (int) $pdo->lastInsertId();
    }

    if (isset($_POST['opciones_form_present'])) {
        $opcionIds = $_POST['opcion_id'] ?? [];
        $opcionNombres = $_POST['opcion_nombre'] ?? [];
        $opcionPlazas = $_POST['opcion_plazas'] ?? [];
        $opcionCategorias = $_POST['opcion_categoria'] ?? [];
        $opcionDescripciones = $_POST['opcion_descripcion'] ?? [];
        $idsConservados = [];

        if (is_array($opcionNombres)) {
            foreach ($opcionNombres as $i => $nombre) {
                $nombre = trim((string) $nombre);
                $opcionId = (int) ($opcionIds[$i] ?? 0);
                $maxPlazas = trim((string) ($opcionPlazas[$i] ?? ''));
                $categoria = trim((string) ($opcionCategorias[$i] ?? '')) ?: 'Comida';
                $descripcion = trim((string) ($opcionDescripciones[$i] ?? ''));

                if ($nombre === '') {
                    continue;
                }

                $params = [
                    'acto_id' => $actoId,
                    'nombre' => $nombre,
                    'categoria' => $categoria,
                    'descripcion' => $descripcion,
                    'max_plazas' => $maxPlazas !== '' ? (int) $maxPlazas : null,
                ];

                if ($opcionId > 0) {
                    $params['id'] = $opcionId;

                    $pdo->prepare("
                        UPDATE opciones_comida
                        SET nombre=:nombre,
                            categoria=:categoria,
                            descripcion=:descripcion,
                            max_plazas=:max_plazas,
                            is_active=1
                        WHERE id=:id AND acto_id=:acto_id
                    ")->execute($params);

                    $idsConservados[] = $opcionId;
                } else {
                    $pdo->prepare("
                        INSERT INTO opciones_comida (acto_id, categoria, nombre, descripcion, max_plazas, is_active)
                        VALUES (:acto_id, :categoria, :nombre, :descripcion, :max_plazas, 1)
                    ")->execute($params);

                    $idsConservados[] = (int) $pdo->lastInsertId();
                }
            }
        }

        $notInSql = '';
        $extraParams = [];
        if ($idsConservados) {
            $notInSql = 'AND oc.id NOT IN (' . implode(',', array_fill(0, count($idsConservados), '?')) . ')';
            $extraParams = $idsConservados;
        }

        $paramsDelete = array_merge([$actoId], $extraParams);
        $pdo->prepare("
            DELETE oc FROM opciones_comida oc
            LEFT JOIN reservas r ON r.opcion_comida_id = oc.id OR r.invitado_opcion_comida_id = oc.id
            LEFT JOIN reserva_invitados ri ON ri.opcion_comida_id = oc.id
            LEFT JOIN reserva_opciones ro ON ro.opcion_comida_id = oc.id
            LEFT JOIN reserva_invitado_opciones rio ON rio.opcion_comida_id = oc.id
            WHERE oc.acto_id = ?
              $notInSql
              AND r.id IS NULL
              AND ri.id IS NULL
              AND ro.id IS NULL
              AND rio.id IS NULL
        ")->execute($paramsDelete);

        $paramsInactive = array_merge([$actoId], $extraParams);
        $pdo->prepare("
            UPDATE opciones_comida oc
            SET oc.is_active = 0
            WHERE oc.acto_id = ?
              $notInSql
              AND (
                  EXISTS (SELECT 1 FROM reservas r WHERE r.opcion_comida_id = oc.id OR r.invitado_opcion_comida_id = oc.id)
                  OR EXISTS (SELECT 1 FROM reserva_invitados ri WHERE ri.opcion_comida_id = oc.id)
                  OR EXISTS (SELECT 1 FROM reserva_opciones ro WHERE ro.opcion_comida_id = oc.id)
                  OR EXISTS (SELECT 1 FROM reserva_invitado_opciones rio WHERE rio.opcion_comida_id = oc.id)
              )
        ")->execute($paramsInactive);
    }

    log_activity(
        $pdo,
        $esNuevoActo ? 'Creado' : 'Modificado',
        'actos',
        ($esNuevoActo ? 'Acto creado: ' : 'Acto modificado: ') . $data['titulo'],
        $actoId,
        $data['titulo']
    );

    if ($esNuevoActo) {
        require_once __DIR__ . '/enviar-notificacion.php';

        enviarNotificacionPush(
            'Nuevo acto disponible',
            'Se ha creado un nuevo acto: ' . $data['titulo'],
            '/mis_actos.php'
        );
    }

    redirect('acto_detalle.php?id=' . $actoId);
}

$edit = null;
$opcionesEdit = [];

if (!empty($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM actos WHERE id=:id");
    $stmt->execute(['id' => (int) $_GET['edit']]);
    $edit = $stmt->fetch();

    if ($edit) {
        $stmt = $pdo->prepare("SELECT * FROM opciones_comida WHERE acto_id=:acto_id ORDER BY categoria ASC, id ASC");
        $stmt->execute(['acto_id' => (int) $edit['id']]);
        $opcionesEdit = $stmt->fetchAll();
    }
}

$actos = $pdo->query("
    SELECT a.*,
           COALESCE(NULLIF(TRIM(CONCAT_WS(' ', cu_f.nombre, cu_f.apellidos)), ''), cu.dni, 'Sistema') AS creado_por_nombre,
           COALESCE(NULLIF(TRIM(CONCAT_WS(' ', uu_f.nombre, uu_f.apellidos)), ''), uu.dni, 'Sin editar') AS editado_por_nombre,
           (
               SELECT COUNT(*) FROM reservas r
               WHERE r.acto_id = a.id AND r.estado = 'confirmada'
           ) + (
               SELECT COUNT(*) FROM reserva_invitados ri
               INNER JOIN reservas r2 ON r2.id = ri.reserva_id
               WHERE r2.acto_id = a.id AND r2.estado = 'confirmada'
           ) AS reservas_confirmadas,
           (
               SELECT COUNT(*) FROM reservas rc
               WHERE rc.acto_id = a.id AND rc.estado = 'cancelada'
           ) AS reservas_canceladas,
           COUNT(DISTINCT oc.id) AS opciones
    FROM actos a
    LEFT JOIN opciones_comida oc ON oc.acto_id = a.id AND oc.is_active = 1
    LEFT JOIN users cu ON cu.id = a.created_by
    LEFT JOIN falleros cu_f ON cu_f.id = cu.fallero_id
    LEFT JOIN users uu ON uu.id = a.updated_by
    LEFT JOIN falleros uu_f ON uu_f.id = uu.fallero_id
    GROUP BY a.id
    ORDER BY a.created_at DESC, a.id DESC
")->fetchAll();

include __DIR__ . '/header.php';
include __DIR__ . '/sidebar.php';
?>

<style>
    .actos-page-layout {
        display: flex;
        flex-direction: column;
        gap: 28px;
    }

    .actos-form-card {
        width: 100%;
        position: relative;
    }

    .actos-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px 20px;
    }

    .actos-form-grid .form-field-full {
        grid-column: 1 / -1;
    }

    .actos-form-grid textarea {
        min-height: 95px;
        resize: vertical;
    }

    .acto-current-image {
        width: 100%;
        max-height: 180px;
        object-fit: cover;
        border-radius: 16px;
        margin-top: 10px;
    }

    .options-rows {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .option-row {
        display: grid;
        grid-template-columns: 1fr 120px 1.4fr auto;
        gap: 10px;
        align-items: center;
    }

    .actos-list-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        margin-bottom: 18px;
    }

    .actos-list-header h2 {
        margin: 0;
    }

    .actos-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 22px;
    }

    .acto-card {
        height: 100%;
    }

    .acto-card-main {
        display: block;
        text-decoration: none;
        color: inherit;
    }

    .acto-card-img {
        width: 100%;
        height: 160px;
        object-fit: cover;
        border-radius: 18px;
        margin-bottom: 14px;
    }

    .acto-card-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-top: 14px;
    }

    .acto-card-actions form {
        margin: 0;
    }

    @media (max-width: 1200px) {
        .actos-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .actos-form-grid {
            grid-template-columns: 1fr;
        }

        .actos-grid {
            grid-template-columns: 1fr;
        }

        .option-row {
            grid-template-columns: 1fr;
        }

        .actos-list-header {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>

<main class="main-dashboard">
    <header class="dashboard-topbar">
        <button class="mobile-menu-btn" type="button" onclick="toggleSidebar()">☰</button>
        <div>
            <h1>Actos</h1>
            <p>Actos organizados en tarjetas. Entra en cada acto para ver reservas y opciones.</p>
        </div>
        <div class="topbar-actions">
            <a href="index.php" class="topbar-btn">← Panel</a>
            <a href="logout.php" class="topbar-btn">➤ Salir</a>
        </div>
    </header>

    <section class="dashboard-content actos-page-layout">

        <div class="card-modern actos-form-card">
            <h2 class="h5 mb-3"><?= $edit ? 'Editar acto' : 'Nuevo acto' ?></h2>

            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="id" value="<?= e($edit['id'] ?? '') ?>">

                <div class="actos-form-grid">
                    <div>
                        <label class="form-label">Nombre del acto</label>
                        <input class="form-control" name="titulo" value="<?= e($edit['titulo'] ?? '') ?>" placeholder="Ej. Comida del sábado, Cena de la plantà..." required>
                    </div>

                    <div>
                        <label class="form-label">Imagen del acto</label>
                        <input class="form-control" type="file" name="imagen" accept="image/jpeg,image/png,image/webp">

                        <?php if (!empty($edit['imagen'])): ?>
                            <img src="<?= e($edit['imagen']) ?>" alt="Imagen actual" class="acto-current-image">
                            <small class="text-muted">Si subes otra imagen, se sustituirá la actual.</small>
                        <?php endif; ?>
                    </div>

                    <div class="form-field-full">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" rows="3"><?= e($edit['descripcion'] ?? '') ?></textarea>
                    </div>

                    <div>
                        <label class="form-label">Fecha</label>
                        <input class="form-control" type="date" name="fecha" value="<?= e($edit['fecha'] ?? date('Y-m-d')) ?>" required>
                    </div>

                    <div>
                        <label class="form-label">Hora</label>
                        <input class="form-control" type="time" name="hora" value="<?= e(substr((string)($edit['hora'] ?? ''), 0, 5)) ?>">
                    </div>

                    <div>
                        <label class="form-label">Ubicación</label>
                        <input class="form-control" name="ubicacion" value="<?= e($edit['ubicacion'] ?? '') ?>">
                    </div>

                    <div>
                        <label class="form-label">Tipo</label>
                        <select class="form-select" name="tipo">
                            <?php foreach (['Comida','Cena','Pasacalles','Reunion','Loteria','Especial'] as $tipo): ?>
                                <option value="<?= $tipo ?>" <?= (($edit['tipo'] ?? '') === $tipo) ? 'selected' : '' ?>><?= $tipo ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Plazas</label>
                        <input class="form-control" type="number" name="max_plazas" value="<?= e((string)($edit['max_plazas'] ?? '')) ?>">
                    </div>

                    <div>
                        <label class="form-label">Estado</label>
                        <select class="form-select" name="estado">
                            <?php foreach (['Abierto','Cerrado','Cancelado'] as $estado): ?>
                                <option value="<?= $estado ?>" <?= (($edit['estado'] ?? '') === $estado) ? 'selected' : '' ?>><?= $estado ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-field-full">
                        <input type="hidden" name="opciones_form_present" value="1">
                        <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                            <div>
                                <label class="form-label mb-0">Bloques y opciones del acto</label>
                                <small class="text-muted d-block">Crea bloques como Comida, Merienda o Cena, y añade sus opciones dentro.</small>
                            </div>
                            <button class="btn btn-sm btn-primary" type="button" data-food-action="add-block">+ Añadir bloque</button>
                        </div>

                        <div id="foodBlocks" class="food-blocks">
                            <?php
                            $rows = $opcionesEdit ?: [];
                            $bloques = [];
                            foreach ($rows as $opcion) {
                                $categoria = trim((string)($opcion['categoria'] ?? '')) ?: 'Comida';
                                $bloques[$categoria][] = $opcion;
                            }
                            foreach ($bloques as $categoria => $opcionesBloque):
                            ?>
                                <div class="food-block">
                                    <div class="food-block-header">
                                        <div class="food-block-title-wrap">
                                            <label class="form-label">Nombre del bloque</label>
                                            <input class="form-control food-block-title" value="<?= e($categoria) ?>" placeholder="Ej. Comida, Merienda, Cena">
                                        </div>
                                        <button class="btn btn-outline-danger btn-sm" type="button" data-food-action="remove-block">Eliminar bloque</button>
                                    </div>

                                    <div class="food-block-options">
                                        <?php foreach ($opcionesBloque as $opcion): ?>
                                            <div class="option-row">
                                                <input type="hidden" name="opcion_id[]" value="<?= e((string)($opcion['id'] ?? '')) ?>">
                                                <input type="hidden" class="js-option-category" name="opcion_categoria[]" value="<?= e($categoria) ?>">
                                                <input class="form-control" name="opcion_nombre[]" placeholder="Opción. Ej. Paella" value="<?= e($opcion['nombre'] ?? '') ?>">
                                                <input class="form-control" name="opcion_plazas[]" type="number" placeholder="Plazas" value="<?= e((string)($opcion['max_plazas'] ?? '')) ?>">
                                                <input class="form-control" name="opcion_descripcion[]" placeholder="Descripción opcional" value="<?= e($opcion['descripcion'] ?? '') ?>">
                                                <button class="btn btn-outline-danger" type="button" data-food-action="remove-option">×</button>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <button class="btn btn-light btn-sm mt-2" type="button" data-food-action="add-option">+ Añadir opción a este bloque</button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="form-field-full">
                        <button class="btn btn-primary w-100"><?= $edit ? 'Guardar cambios' : 'Crear acto' ?></button>

                        <?php if ($edit): ?>
                            <a class="btn btn-light w-100 mt-2" href="actos.php">Cancelar edición</a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>

        <div>
            <div class="actos-list-header">
                <div>
                    <h2 class="section-title">Actos creados</h2>
                    <p class="text-muted mb-0">Listado de actos mostrados de tres en tres.</p>
                </div>
            </div>

            <div class="actos-grid">
                <?php foreach ($actos as $acto): ?>
                    <article class="acto-card <?= strtolower((string)$acto['estado']) !== 'abierto' ? 'acto-card-cerrado' : '' ?>">
                        <a class="acto-card-main" href="acto_detalle.php?id=<?= (int) $acto['id'] ?>">
                            <?php if (!empty($acto['imagen'])): ?>
                                <img src="<?= e($acto['imagen']) ?>" alt="<?= e($acto['titulo']) ?>" class="acto-card-img">
                            <?php endif; ?>

                            <div class="acto-card-badge <?= e($acto['estado']) ?>"><?= e($acto['estado']) ?></div>
                            <h3><?= e($acto['titulo']) ?></h3>
                            <p><?= e($acto['fecha']) ?> <?= e(substr((string)$acto['hora'], 0, 5)) ?></p>

                            <div class="acto-card-meta">
                                <span><?= e($acto['tipo']) ?></span>
                                <span><?= e((string)$acto['reservas_confirmadas']) ?> reservas</span>
                                <span><?= e((string)$acto['opciones']) ?> opciones de comida</span>
                            </div>

                            <div class="acto-card-link">Ver detalle y recuentos →</div>
                        </a>

                            <div class="acto-card-actions">
                            <a class="btn btn-sm btn-light" href="actos.php?edit=<?= (int) $acto['id'] ?>">Editar</a>

                            <form method="post" onsubmit="return confirm('¿Eliminar acto?')">
                                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= (int) $acto['id'] ?>">
                                <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                            </form>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>

    </section>
</main>


<script>
(function () {
    if (window.__foodBlocksReady) return;
    window.__foodBlocksReady = true;

    function getBlockName(block) {
        const input = block ? block.querySelector('.food-block-title') : null;
        const value = input ? input.value.trim() : '';
        return value || 'Comida';
    }

    function syncBlock(block) {
        if (!block) return;
        const categoria = getBlockName(block);
        block.querySelectorAll('.js-option-category').forEach(function (input) {
            input.value = categoria;
        });
    }

    function createOption(categoria) {
        const row = document.createElement('div');
        row.className = 'option-row';
        row.innerHTML = '<input type="hidden" name="opcion_id[]" value="">' +
            '<input type="hidden" class="js-option-category" name="opcion_categoria[]" value="">' +
            '<input class="form-control" name="opcion_nombre[]" placeholder="Opción. Ej. Paella">' +
            '<input class="form-control" name="opcion_plazas[]" type="number" placeholder="Plazas">' +
            '<input class="form-control" name="opcion_descripcion[]" placeholder="Descripción opcional">' +
            '<button class="btn btn-outline-danger" type="button" data-food-action="remove-option">×</button>';
        row.querySelector('.js-option-category').value = categoria;
        return row;
    }

    window.addFoodBlock = window.addFoodBlock || function () {
        const wrapper = document.getElementById('foodBlocks');
        if (!wrapper) return;
        const block = document.createElement('div');
        block.className = 'food-block';
        block.innerHTML = '<div class="food-block-header">' +
            '<div class="food-block-title-wrap">' +
            '<label class="form-label">Nombre del bloque</label>' +
            '<input class="form-control food-block-title" value="" placeholder="Ej. Comida, Merienda, Cena">' +
            '</div>' +
            '<button class="btn btn-outline-danger btn-sm" type="button" data-food-action="remove-block">Eliminar bloque</button>' +
            '</div>' +
            '<div class="food-block-options"></div>' +
            '<button class="btn btn-light btn-sm mt-2" type="button" data-food-action="add-option">+ Añadir opción a este bloque</button>';
        wrapper.appendChild(block);
        block.querySelector('.food-block-options').appendChild(createOption('Comida'));
        const title = block.querySelector('.food-block-title');
        if (title) title.focus();
    };

    window.addOptionToBlock = window.addOptionToBlock || function (button) {
        const block = button.closest('.food-block');
        if (!block) return;
        syncBlock(block);
        const list = block.querySelector('.food-block-options');
        const row = createOption(getBlockName(block));
        list.appendChild(row);
        const input = row.querySelector('input[name="opcion_nombre[]"]');
        if (input) input.focus();
    };

    window.removeFoodBlock = window.removeFoodBlock || function (button) {
        const block = button.closest('.food-block');
        if (block) block.remove();
    };

    window.removeOptionRow = window.removeOptionRow || function (button) {
        const row = button.closest('.option-row');
        if (row) row.remove();
    };

    document.addEventListener('click', function (event) {
        const btn = event.target.closest('[data-food-action]');
        if (!btn) return;
        event.preventDefault();
        const action = btn.getAttribute('data-food-action');
        if (action === 'add-block') window.addFoodBlock();
        if (action === 'add-option') window.addOptionToBlock(btn);
        if (action === 'remove-block') window.removeFoodBlock(btn);
        if (action === 'remove-option') window.removeOptionRow(btn);
    });

    document.addEventListener('input', function (event) {
        if (event.target && event.target.classList.contains('food-block-title')) {
            syncBlock(event.target.closest('.food-block'));
        }
    });

    document.addEventListener('submit', function (event) {
        if (!event.target || !event.target.querySelector('#foodBlocks')) return;
        event.target.querySelectorAll('.food-block').forEach(syncBlock);
    });
})();
</script>

<?php include __DIR__ . '/footer.php'; ?>