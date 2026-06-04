-- Ejecutar una vez en phpMyAdmin antes de usar el código actualizado.
-- Añade auditoría de creación/edición en las tablas principales.

ALTER TABLE actos ADD COLUMN IF NOT EXISTS updated_by INT(11) DEFAULT NULL AFTER updated_at;
ALTER TABLE avisos ADD COLUMN IF NOT EXISTS updated_by INT(11) DEFAULT NULL AFTER updated_at;
ALTER TABLE juntas ADD COLUMN IF NOT EXISTS updated_by INT(11) DEFAULT NULL AFTER updated_at;
ALTER TABLE junta_archivos ADD COLUMN IF NOT EXISTS deleted_by INT(11) DEFAULT NULL AFTER created_at;
ALTER TABLE falleros ADD COLUMN IF NOT EXISTS created_by INT(11) DEFAULT NULL AFTER familia_id;
ALTER TABLE falleros ADD COLUMN IF NOT EXISTS updated_by INT(11) DEFAULT NULL AFTER updated_at;
ALTER TABLE familias ADD COLUMN IF NOT EXISTS created_by INT(11) DEFAULT NULL AFTER observaciones;
ALTER TABLE familias ADD COLUMN IF NOT EXISTS updated_by INT(11) DEFAULT NULL AFTER updated_at;
ALTER TABLE reservas ADD COLUMN IF NOT EXISTS created_by INT(11) DEFAULT NULL AFTER observaciones;
ALTER TABLE reservas ADD COLUMN IF NOT EXISTS updated_by INT(11) DEFAULT NULL AFTER updated_at;

CREATE INDEX IF NOT EXISTS idx_actos_updated_by ON actos(updated_by);
CREATE INDEX IF NOT EXISTS idx_avisos_updated_by ON avisos(updated_by);
CREATE INDEX IF NOT EXISTS idx_juntas_updated_by ON juntas(updated_by);
CREATE INDEX IF NOT EXISTS idx_falleros_created_by ON falleros(created_by);
CREATE INDEX IF NOT EXISTS idx_falleros_updated_by ON falleros(updated_by);
CREATE INDEX IF NOT EXISTS idx_familias_created_by ON familias(created_by);
CREATE INDEX IF NOT EXISTS idx_familias_updated_by ON familias(updated_by);


-- Campos nuevos para identificar el registro afectado en la pestaña de Auditoría.
ALTER TABLE activity_logs ADD COLUMN IF NOT EXISTS registro_id INT(11) DEFAULT NULL AFTER modulo;
ALTER TABLE activity_logs ADD COLUMN IF NOT EXISTS registro_nombre VARCHAR(255) DEFAULT NULL AFTER registro_id;
CREATE INDEX IF NOT EXISTS idx_activity_logs_registro ON activity_logs(modulo, registro_id);

-- Limpieza de auditoría: no mostrar/guardar actividad de auth, perfil ni acciones de usuarios falleros.
DELETE al
FROM activity_logs al
LEFT JOIN users u ON u.id = al.user_id
WHERE al.modulo IN ('auth', 'perfil')
   OR u.id IS NULL
   OR u.role <> 'admin';

-- Ya no se guarda la IP en auditoría.
ALTER TABLE activity_logs DROP COLUMN IF EXISTS ip_address;


-- Normalizar acciones antiguas a español para que no aparezcan como save/delete/update.
UPDATE activity_logs SET accion = 'Creado' WHERE LOWER(accion) IN ('create', 'created', 'crear');
UPDATE activity_logs SET accion = 'Modificado' WHERE LOWER(accion) IN ('update', 'updated', 'edit', 'editar', 'save', 'guardar');
UPDATE activity_logs SET accion = 'Eliminado' WHERE LOWER(accion) IN ('delete', 'deleted', 'eliminar');
UPDATE activity_logs SET accion = 'Aprobado' WHERE LOWER(accion) IN ('approve', 'aprobado');
UPDATE activity_logs SET accion = 'Rechazado' WHERE LOWER(accion) IN ('reject', 'rechazado');
UPDATE activity_logs SET accion = 'Cancelado' WHERE LOWER(accion) IN ('cancel', 'cancelado');

-- Rellenar el nombre del acto en registros antiguos cuando sea posible.
UPDATE activity_logs al
INNER JOIN actos a ON al.modulo = 'actos' AND al.registro_id = a.id
SET al.registro_nombre = a.titulo
WHERE al.registro_nombre IS NULL;
