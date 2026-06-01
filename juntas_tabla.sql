-- ─────────────────────────────────────────────────────────────────
-- Tablas para el módulo de Juntas
-- Ejecutar en: u287751603_falla
-- ─────────────────────────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS `juntas` (
  `id`          INT(11)      NOT NULL AUTO_INCREMENT,
  `nombre`      VARCHAR(255) NOT NULL,
  `fecha`       DATE         NOT NULL,
  `descripcion` TEXT         DEFAULT NULL,
  `created_by`  INT(11)      DEFAULT NULL,
  `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  DATETIME     DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_fecha` (`fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `junta_archivos` (
  `id`               INT(11)      NOT NULL AUTO_INCREMENT,
  `junta_id`         INT(11)      NOT NULL,
  `nombre_original`  VARCHAR(255) NOT NULL,
  `ruta`             VARCHAR(500) NOT NULL,
  `created_by`       INT(11)      DEFAULT NULL,
  `created_at`       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_junta_id` (`junta_id`),
  CONSTRAINT `fk_junta_archivos_junta`
    FOREIGN KEY (`junta_id`) REFERENCES `juntas` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
