Cambios de auditoría aplicados
==============================

1) La auditoría solo registra acciones hechas por usuarios administradores.
2) No se guardan ni se muestran inicios de sesión, perfil, cambios de contraseña ni acciones de falleros normales.
3) Si un fallero se apunta a un acto, cancela una reserva o realiza acciones desde su zona privada, no aparece en auditoría.
4) El campo IP se ha quitado de la vista y del INSERT de auditoría. En la migración también se elimina la columna ip_address de activity_logs.
5) La pantalla auditoria.php filtra registros antiguos para mostrar solo líneas de administradores.

Antes de subir el código, ejecuta audit_migration.sql en phpMyAdmin.


Actualización nueva
===================
6) En la pestaña Auditoría se ha añadido la columna "Registro".
7) Cuando la acción afecta a un acto, el registro muestra el nombre del acto afectado.
8) Las acciones se muestran en español: Creado, Modificado, Eliminado, Aprobado, Rechazado, Cancelado.
9) Ya no aparecerá "save" en pantalla; las acciones antiguas se normalizan con audit_migration.sql.
