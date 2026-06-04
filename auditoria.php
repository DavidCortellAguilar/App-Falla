<?php
require_once __DIR__ . '/config.php';
require_admin();
ensure_audit_columns($pdo);
$page_title = 'Auditoría';

function auditoria_modulo_label(string $modulo): string
{
    $map = [
        'actos' => 'Actos',
        'avisos' => 'Avisos',
        'juntas' => 'Juntas',
        'junta_archivos' => 'Archivos de junta',
        'falleros' => 'Falleros',
        'familias' => 'Familias',
        'reservas' => 'Reservas',
    ];
    return $map[$modulo] ?? ucfirst($modulo);
}

function auditoria_accion_label(string $accion): string
{
    if (function_exists('audit_action_label')) {
        return audit_action_label($accion);
    }

    $map = [
        'create' => 'Creado',
        'update' => 'Modificado',
        'save' => 'Modificado',
        'delete' => 'Eliminado',
        'approve' => 'Aprobado',
        'reject' => 'Rechazado',
        'cancel' => 'Cancelado',
        'pago' => 'Pago',
    ];
    return $map[strtolower($accion)] ?? ucfirst($accion);
}


$modulo = trim($_GET['modulo'] ?? '');
$accion = trim($_GET['accion'] ?? '');
$q = trim($_GET['q'] ?? '');

$where = ["u.role = 'admin'", "al.modulo NOT IN ('auth', 'perfil')"];
$params = [];

if ($modulo !== '') {
    $where[] = 'al.modulo = :modulo';
    $params['modulo'] = $modulo;
}
if ($accion !== '') {
    $where[] = 'al.accion = :accion';
    $params['accion'] = $accion;
}
if ($q !== '') {
    $where[] = '(al.descripcion LIKE :q OR al.modulo LIKE :q OR al.accion LIKE :q OR al.registro_nombre LIKE :q OR u.dni LIKE :q OR f.nombre LIKE :q OR f.apellidos LIKE :q)';
    $params['q'] = '%' . $q . '%';
}

$sql = "
    SELECT al.*,
           COALESCE(NULLIF(TRIM(CONCAT_WS(' ', f.nombre, f.apellidos)), ''), u.dni, 'Administrador') AS usuario_nombre,
           COALESCE(al.registro_nombre, a.titulo) AS registro_nombre_mostrado
    FROM activity_logs al
    INNER JOIN users u ON u.id = al.user_id AND u.role = 'admin'
    LEFT JOIN falleros f ON f.id = u.fallero_id
    LEFT JOIN actos a ON al.modulo = 'actos' AND a.id = al.registro_id
    WHERE " . implode(' AND ', $where) . "
    ORDER BY al.created_at DESC, al.id DESC
    LIMIT 300
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$logs = $stmt->fetchAll();

$modulos = $pdo->query("
    SELECT DISTINCT al.modulo
    FROM activity_logs al
    INNER JOIN users u ON u.id = al.user_id AND u.role = 'admin'
    WHERE al.modulo IS NOT NULL
      AND al.modulo <> ''
      AND al.modulo NOT IN ('auth', 'perfil')
    ORDER BY al.modulo
")->fetchAll(PDO::FETCH_COLUMN);

$acciones = $pdo->query("
    SELECT DISTINCT al.accion
    FROM activity_logs al
    INNER JOIN users u ON u.id = al.user_id AND u.role = 'admin'
    WHERE al.accion IS NOT NULL
      AND al.accion <> ''
      AND al.modulo NOT IN ('auth', 'perfil')
    ORDER BY al.accion
")->fetchAll(PDO::FETCH_COLUMN);

include __DIR__ . '/header.php';
include __DIR__ . '/sidebar.php';
?>

<main class="main-dashboard">
    <header class="dashboard-topbar">
        <button class="mobile-menu-btn" type="button" onclick="toggleSidebar()">☰</button>
        <div>
            <h1>Auditoría</h1>
            <p>Registro interno de acciones realizadas únicamente por administradores.</p>
        </div>
        <div class="topbar-actions">
            <a href="index.php" class="topbar-btn">← Panel</a>
            <a href="logout.php" class="topbar-btn">➤ Salir</a>
        </div>
    </header>

    <section class="dashboard-content">
        <div class="card-modern mb-4">
            <form class="row g-2 align-items-end" method="get">
                <div class="col-md-4">
                    <label class="form-label">Buscar</label>
                    <input class="form-control" name="q" value="<?= e($q) ?>" placeholder="Administrador, acción, descripción...">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Módulo</label>
                    <select class="form-select" name="modulo">
                        <option value="">Todos</option>
                        <?php foreach ($modulos as $m): ?>
                            <option value="<?= e($m) ?>" <?= $modulo === $m ? 'selected' : '' ?>><?= e(auditoria_modulo_label($m)) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Acción</label>
                    <select class="form-select" name="accion">
                        <option value="">Todas</option>
                        <?php foreach ($acciones as $a): ?>
                            <option value="<?= e($a) ?>" <?= $accion === $a ? 'selected' : '' ?>><?= e(auditoria_accion_label($a)) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button class="btn btn-primary">Filtrar</button>
                </div>
            </form>
        </div>

        <div class="card-modern table-card">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Administrador</th>
                        <th>Módulo</th>
                        <th>Registro</th>
                        <th>Acción</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><?= e(date('d/m/Y H:i', strtotime($log['created_at']))) ?></td>
                            <td><?= e($log['usuario_nombre']) ?></td>
                            <td><span class="badge bg-light text-dark"><?= e(auditoria_modulo_label($log['modulo'])) ?></span></td>
                            <td><?= e($log['registro_nombre_mostrado'] ?: '-') ?></td>
                            <td><strong><?= e(auditoria_accion_label($log['accion'])) ?></strong></td>
                            <td><?= e($log['descripcion']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$logs): ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">No hay registros con esos filtros.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="small text-muted mt-3">Solo se muestran movimientos de administradores. No se registran inicios de sesión, perfil ni acciones realizadas por falleros.</div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/footer.php'; ?>
