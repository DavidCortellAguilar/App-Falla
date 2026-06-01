<?php
/**
 * Helper del módulo de Juntas.
 * Crea las tablas si todavía no existen y centraliza la subida de documentos.
 */

function ensure_juntas_tables(PDO $pdo): void
{
    $pdo->exec("CREATE TABLE IF NOT EXISTS juntas (
        id INT(11) NOT NULL AUTO_INCREMENT,
        nombre VARCHAR(255) NOT NULL,
        fecha DATE NOT NULL,
        descripcion TEXT DEFAULT NULL,
        created_by INT(11) DEFAULT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT NULL,
        PRIMARY KEY (id),
        KEY idx_fecha (fecha)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS junta_archivos (
        id INT(11) NOT NULL AUTO_INCREMENT,
        junta_id INT(11) NOT NULL,
        nombre_original VARCHAR(255) NOT NULL,
        ruta VARCHAR(500) NOT NULL,
        created_by INT(11) DEFAULT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY idx_junta_id (junta_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
}

function subir_archivo_junta(): ?string
{
    if (empty($_FILES['archivo']['name']) || ($_FILES['archivo']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return null;
    }

    if ($_FILES['archivo']['size'] > 20 * 1024 * 1024) {
        die('El archivo no puede pesar más de 20 MB.');
    }

    $uploadDir = __DIR__ . '/uploads/juntas/';
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true)) {
        die('No se ha podido crear la carpeta uploads/juntas/.');
    }

    if (!is_writable($uploadDir)) {
        die('La carpeta uploads/juntas/ no tiene permisos de escritura. Dale permisos 775 o 755 según tu hosting.');
    }

    $ext = strtolower(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION));
    $permitidas = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'odt', 'ods', 'jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ext, $permitidas, true)) {
        die('Formato no permitido. Usa PDF, Word, Excel o imagen.');
    }

    $nombreOrig = pathinfo($_FILES['archivo']['name'], PATHINFO_FILENAME);
    $nombreOrig = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $nombreOrig);
    $nombreOrig = substr($nombreOrig ?: 'documento', 0, 40);

    $nombreArchivo = 'junta_' . date('Ymd_His') . '_' . bin2hex(random_bytes(6)) . '_' . $nombreOrig . '.' . $ext;
    $rutaFisica = $uploadDir . $nombreArchivo;
    $rutaWeb = 'uploads/juntas/' . $nombreArchivo;

    if (!move_uploaded_file($_FILES['archivo']['tmp_name'], $rutaFisica)) {
        die('Error al subir el archivo. Revisa permisos de la carpeta uploads/juntas/.');
    }

    if (!is_file($rutaFisica)) {
        die('El archivo se ha intentado subir, pero no se ha guardado físicamente en el servidor.');
    }

    return $rutaWeb;
}

function archivo_junta_existe(?string $ruta): bool
{
    if (!$ruta) {
        return false;
    }

    $rutaRelativa = str_replace(['..', '\\'], ['', '/'], (string) $ruta);
    return is_file(__DIR__ . '/' . ltrim($rutaRelativa, '/'));
}
