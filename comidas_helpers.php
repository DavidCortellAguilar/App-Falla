<?php

declare(strict_types=1);

function ensure_comidas_multiples_schema(PDO $pdo): void
{
    static $done = false;
    if ($done) return;
    $done = true;

    try {
        $col = $pdo->query("SHOW COLUMNS FROM opciones_comida LIKE 'categoria'")->fetch();
        if (!$col) {
            $pdo->exec("ALTER TABLE opciones_comida ADD COLUMN categoria VARCHAR(80) NOT NULL DEFAULT 'Comida' AFTER acto_id");
        }
    } catch (Throwable $e) {}

    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS reserva_opciones (
            id INT AUTO_INCREMENT PRIMARY KEY,
            reserva_id INT NOT NULL,
            opcion_comida_id INT NOT NULL,
            categoria VARCHAR(80) NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY uniq_reserva_categoria (reserva_id, categoria),
            KEY idx_reserva (reserva_id),
            KEY idx_opcion (opcion_comida_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    } catch (Throwable $e) {}

    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS reserva_invitado_opciones (
            id INT AUTO_INCREMENT PRIMARY KEY,
            reserva_invitado_id INT NOT NULL,
            opcion_comida_id INT NOT NULL,
            categoria VARCHAR(80) NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY uniq_invitado_categoria (reserva_invitado_id, categoria),
            KEY idx_invitado (reserva_invitado_id),
            KEY idx_opcion (opcion_comida_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    } catch (Throwable $e) {}

    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS qr_validaciones_bloques (
            id INT AUTO_INCREMENT PRIMARY KEY,
            reserva_id INT NOT NULL,
            categoria VARCHAR(80) NOT NULL,
            opcion_comida_id INT NULL,
            validado_por INT NULL,
            validado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY uniq_reserva_categoria (reserva_id, categoria),
            KEY idx_reserva (reserva_id),
            KEY idx_opcion (opcion_comida_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    } catch (Throwable $e) {}
}

function opciones_comida_por_categoria(PDO $pdo, int $actoId): array
{
    ensure_comidas_multiples_schema($pdo);
    $stmt = $pdo->prepare("SELECT * FROM opciones_comida WHERE acto_id=:id AND is_active=1 ORDER BY categoria ASC, id ASC");
    $stmt->execute(['id' => $actoId]);
    $grupos = [];
    foreach ($stmt->fetchAll() as $opcion) {
        $categoria = trim((string)($opcion['categoria'] ?? '')) ?: 'Comida';
        $grupos[$categoria][] = $opcion;
    }
    return $grupos;
}

function primera_opcion_de_grupos(array $selecciones): ?int
{
    foreach ($selecciones as $opcionId) {
        $opcionId = (int)$opcionId;
        if ($opcionId > 0) return $opcionId;
    }
    return null;
}

function opcion_comida_valida(PDO $pdo, ?int $opcionId, int $actoId, ?string $categoria = null): ?array
{
    if (!$opcionId) return null;
    $sql = "SELECT id, categoria FROM opciones_comida WHERE id=:id AND acto_id=:acto_id AND is_active=1";
    $params = ['id' => $opcionId, 'acto_id' => $actoId];
    if ($categoria !== null) {
        $sql .= " AND categoria=:categoria";
        $params['categoria'] = $categoria;
    }
    $stmt = $pdo->prepare($sql . " LIMIT 1");
    $stmt->execute($params);
    $row = $stmt->fetch();
    return $row ?: null;
}

function guardar_opciones_reserva(PDO $pdo, int $reservaId, int $actoId, array $selecciones): void
{
    $pdo->prepare("DELETE FROM reserva_opciones WHERE reserva_id=:reserva_id")->execute(['reserva_id' => $reservaId]);
    $stmt = $pdo->prepare("INSERT INTO reserva_opciones (reserva_id, opcion_comida_id, categoria) VALUES (:reserva_id, :opcion_id, :categoria)");
    foreach ($selecciones as $categoria => $opcionId) {
        $categoria = trim((string)$categoria);
        $valid = opcion_comida_valida($pdo, (int)$opcionId, $actoId, $categoria);
        if (!$valid) continue;
        $stmt->execute(['reserva_id' => $reservaId, 'opcion_id' => (int)$valid['id'], 'categoria' => $categoria]);
    }
    recalcular_qr_usado_reserva($pdo, $reservaId);
}

function guardar_opciones_invitado(PDO $pdo, int $reservaInvitadoId, int $actoId, array $selecciones): void
{
    $pdo->prepare("DELETE FROM reserva_invitado_opciones WHERE reserva_invitado_id=:id")->execute(['id' => $reservaInvitadoId]);
    $stmt = $pdo->prepare("INSERT INTO reserva_invitado_opciones (reserva_invitado_id, opcion_comida_id, categoria) VALUES (:id, :opcion_id, :categoria)");
    foreach ($selecciones as $categoria => $opcionId) {
        $categoria = trim((string)$categoria);
        $valid = opcion_comida_valida($pdo, (int)$opcionId, $actoId, $categoria);
        if (!$valid) continue;
        $stmt->execute(['id' => $reservaInvitadoId, 'opcion_id' => (int)$valid['id'], 'categoria' => $categoria]);
    }
}

function opciones_texto_por_reserva_ids(PDO $pdo, array $reservaIds): array
{
    ensure_comidas_multiples_schema($pdo);
    $reservaIds = array_values(array_unique(array_filter(array_map('intval', $reservaIds))));
    if (!$reservaIds) return [];

    $ph = implode(',', array_fill(0, count($reservaIds), '?'));
    $stmt = $pdo->prepare("\n        SELECT ro.reserva_id, ro.categoria, oc.nombre\n        FROM reserva_opciones ro\n        INNER JOIN opciones_comida oc ON oc.id = ro.opcion_comida_id\n        WHERE ro.reserva_id IN ($ph)\n        ORDER BY ro.reserva_id ASC, ro.id ASC\n    ");
    $stmt->execute($reservaIds);

    $map = [];
    foreach ($stmt->fetchAll() as $row) {
        $categoria = trim((string)($row['categoria'] ?? '')) ?: 'Comida';
        $nombre = trim((string)($row['nombre'] ?? ''));
        if ($nombre === '') continue;
        $map[(int)$row['reserva_id']][] = $categoria . ': ' . $nombre;
    }
    return $map;
}

function opciones_texto_por_invitado_ids(PDO $pdo, array $invitadoIds): array
{
    ensure_comidas_multiples_schema($pdo);
    $invitadoIds = array_values(array_unique(array_filter(array_map('intval', $invitadoIds))));
    if (!$invitadoIds) return [];

    $ph = implode(',', array_fill(0, count($invitadoIds), '?'));
    $stmt = $pdo->prepare("\n        SELECT rio.reserva_invitado_id, rio.categoria, oc.nombre\n        FROM reserva_invitado_opciones rio\n        INNER JOIN opciones_comida oc ON oc.id = rio.opcion_comida_id\n        WHERE rio.reserva_invitado_id IN ($ph)\n        ORDER BY rio.reserva_invitado_id ASC, rio.id ASC\n    ");
    $stmt->execute($invitadoIds);

    $map = [];
    foreach ($stmt->fetchAll() as $row) {
        $categoria = trim((string)($row['categoria'] ?? '')) ?: 'Comida';
        $nombre = trim((string)($row['nombre'] ?? ''));
        if ($nombre === '') continue;
        $map[(int)$row['reserva_invitado_id']][] = $categoria . ': ' . $nombre;
    }
    return $map;
}

function opciones_texto_fallback(?array $lineas, ?string $opcionAntigua = null): array
{
    if ($lineas) return $lineas;
    $opcionAntigua = trim((string)$opcionAntigua);
    return $opcionAntigua !== '' ? ['Comida: ' . $opcionAntigua] : [];
}

function opciones_texto_plano(?array $lineas, ?string $opcionAntigua = null): string
{
    $lineas = opciones_texto_fallback($lineas, $opcionAntigua);
    return $lineas ? implode("\n", $lineas) : 'Sin opción';
}

function qr_bloques_reserva(PDO $pdo, array $reserva): array
{
    ensure_comidas_multiples_schema($pdo);
    $reservaId = (int)($reserva['id'] ?? 0);
    if ($reservaId <= 0) return [];

    $stmt = $pdo->prepare("\n        SELECT ro.categoria, ro.opcion_comida_id, oc.nombre AS opcion\n        FROM reserva_opciones ro\n        INNER JOIN opciones_comida oc ON oc.id = ro.opcion_comida_id\n        WHERE ro.reserva_id=:reserva_id\n        ORDER BY ro.id ASC\n    ");
    $stmt->execute(['reserva_id' => $reservaId]);
    $bloques = [];
    foreach ($stmt->fetchAll() as $row) {
        $categoria = trim((string)$row['categoria']) ?: 'Comida';
        $bloques[$categoria] = [
            'categoria' => $categoria,
            'opcion_id' => (int)$row['opcion_comida_id'],
            'opcion' => (string)$row['opcion'],
            'validado' => false,
            'validado_en' => null,
        ];
    }

    if (!$bloques && !empty($reserva['opcion_comida_id'])) {
        $categoria = 'Comida';
        $bloques[$categoria] = [
            'categoria' => $categoria,
            'opcion_id' => (int)$reserva['opcion_comida_id'],
            'opcion' => (string)($reserva['opcion'] ?? 'Opción seleccionada'),
            'validado' => false,
            'validado_en' => null,
        ];
    }

    if (!$bloques) return [];

    $stmtVal = $pdo->prepare("SELECT categoria, validado_en FROM qr_validaciones_bloques WHERE reserva_id=:reserva_id");
    $stmtVal->execute(['reserva_id' => $reservaId]);
    foreach ($stmtVal->fetchAll() as $val) {
        $categoria = trim((string)$val['categoria']);
        if (isset($bloques[$categoria])) {
            $bloques[$categoria]['validado'] = true;
            $bloques[$categoria]['validado_en'] = $val['validado_en'];
        }
    }

    return array_values($bloques);
}

function qr_bloques_pendientes(array $bloques): int
{
    $pendientes = 0;
    foreach ($bloques as $bloque) {
        if (empty($bloque['validado'])) $pendientes++;
    }
    return $pendientes;
}

function validar_bloque_qr(PDO $pdo, array $reserva, string $categoria, ?int $userId): array
{
    ensure_comidas_multiples_schema($pdo);
    $categoria = trim($categoria);
    if ($categoria === '') {
        return ['ok' => false, 'mensaje' => 'Bloque no válido.'];
    }

    $bloques = qr_bloques_reserva($pdo, $reserva);
    $bloqueElegido = null;
    foreach ($bloques as $bloque) {
        if (hash_equals($bloque['categoria'], $categoria)) {
            $bloqueElegido = $bloque;
            break;
        }
    }
    if (!$bloqueElegido) {
        return ['ok' => false, 'mensaje' => 'Este QR no tiene ese bloque.'];
    }
    if (!empty($bloqueElegido['validado'])) {
        return ['ok' => false, 'mensaje' => $categoria . ' ya estaba canjeado.'];
    }

    $stmt = $pdo->prepare("\n        INSERT IGNORE INTO qr_validaciones_bloques (reserva_id, categoria, opcion_comida_id, validado_por, validado_en)\n        VALUES (:reserva_id, :categoria, :opcion_id, :validado_por, NOW())\n    ");
    $stmt->execute([
        'reserva_id' => (int)$reserva['id'],
        'categoria' => $categoria,
        'opcion_id' => $bloqueElegido['opcion_id'] ?: null,
        'validado_por' => $userId,
    ]);

    recalcular_qr_usado_reserva($pdo, (int)$reserva['id']);
    return ['ok' => true, 'mensaje' => $categoria . ' canjeado correctamente.'];
}

function recalcular_qr_usado_reserva(PDO $pdo, int $reservaId): void
{
    if ($reservaId <= 0) return;
    try {
        $stmt = $pdo->prepare("SELECT r.*, oc.nombre AS opcion FROM reservas r LEFT JOIN opciones_comida oc ON oc.id=r.opcion_comida_id WHERE r.id=:id");
        $stmt->execute(['id' => $reservaId]);
        $reserva = $stmt->fetch();
        if (!$reserva) return;
        $bloques = qr_bloques_reserva($pdo, $reserva);
        if (!$bloques) return;
        $pendientes = qr_bloques_pendientes($bloques);
        if ($pendientes === 0) {
            $pdo->prepare("UPDATE reservas SET qr_usado=1, fecha_qr_usado=COALESCE(fecha_qr_usado, NOW()), updated_at=NOW() WHERE id=:id")->execute(['id' => $reservaId]);
        } else {
            $pdo->prepare("UPDATE reservas SET qr_usado=0, fecha_qr_usado=NULL, updated_at=NOW() WHERE id=:id")->execute(['id' => $reservaId]);
        }
    } catch (Throwable $e) {}
}
