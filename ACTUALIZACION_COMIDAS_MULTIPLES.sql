ALTER TABLE opciones_comida
  ADD COLUMN IF NOT EXISTS categoria VARCHAR(80) NOT NULL DEFAULT 'Comida' AFTER acto_id;

CREATE TABLE IF NOT EXISTS reserva_opciones (
  id INT AUTO_INCREMENT PRIMARY KEY,
  reserva_id INT NOT NULL,
  opcion_comida_id INT NOT NULL,
  categoria VARCHAR(80) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_reserva_categoria (reserva_id, categoria),
  KEY idx_reserva (reserva_id),
  KEY idx_opcion (opcion_comida_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS reserva_invitado_opciones (
  id INT AUTO_INCREMENT PRIMARY KEY,
  reserva_invitado_id INT NOT NULL,
  opcion_comida_id INT NOT NULL,
  categoria VARCHAR(80) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_invitado_categoria (reserva_invitado_id, categoria),
  KEY idx_invitado (reserva_invitado_id),
  KEY idx_opcion (opcion_comida_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO reserva_opciones (reserva_id, opcion_comida_id, categoria)
SELECT r.id, r.opcion_comida_id, COALESCE(NULLIF(oc.categoria, ''), 'Comida')
FROM reservas r
INNER JOIN opciones_comida oc ON oc.id = r.opcion_comida_id
WHERE r.opcion_comida_id IS NOT NULL;

-- Validaciones de QR por bloque (comida, merienda, cena, etc.)
CREATE TABLE IF NOT EXISTS qr_validaciones_bloques (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reserva_id INT NOT NULL,
    categoria VARCHAR(80) NOT NULL,
    opcion_comida_id INT NULL,
    validado_por INT NULL,
    validado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_reserva_categoria (reserva_id, categoria),
    KEY idx_reserva (reserva_id),
    KEY idx_opcion (opcion_comida_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
