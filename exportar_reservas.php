<?php
require_once __DIR__ . '/config.php';
require_admin();
$acto_id = (int)($_GET['acto_id'] ?? 0);
if (!$acto_id) { exit('Acto no indicado'); }
$stmtActo = $pdo->prepare("SELECT titulo FROM actos WHERE id=:id");
$stmtActo->execute(['id'=>$acto_id]);
$titulo = $stmtActo->fetchColumn() ?: 'Acto';
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="reservas_acto_'.$acto_id.'.csv"');
$out = fopen('php://output', 'w');
fwrite($out, "\xEF\xBB\xBF");
fputcsv($out, ['Acto','Persona','Tipo','DNI','Opción','Invitado de','Estado','Pagada','QR usado','Fecha reserva'], ';');

$stmt = $pdo->prepare("SELECT r.*, f.nombre, f.apellidos, f.dni, oc.nombre AS opcion FROM reservas r JOIN falleros f ON f.id=r.fallero_id LEFT JOIN opciones_comida oc ON oc.id=r.opcion_comida_id WHERE r.acto_id=:acto_id ORDER BY f.apellidos, f.nombre");
$stmt->execute(['acto_id'=>$acto_id]);
$reservas = $stmt->fetchAll();

$reservaIds = array_map(static fn($r) => (int)$r['id'], $reservas);
$invitadosPorReserva = [];
if ($reservaIds) {
    $ph = implode(',', array_fill(0, count($reservaIds), '?'));
    $stmtInv = $pdo->prepare("SELECT ri.*, oc.nombre AS opcion FROM reserva_invitados ri LEFT JOIN opciones_comida oc ON oc.id=ri.opcion_comida_id WHERE ri.reserva_id IN ($ph) ORDER BY ri.id ASC");
    $stmtInv->execute($reservaIds);
    foreach ($stmtInv->fetchAll() as $inv) {
        $invitadosPorReserva[(int)$inv['reserva_id']][] = $inv;
    }
}

foreach ($reservas as $r) {
    $fallero = trim($r['nombre'].' '.$r['apellidos']);
    fputcsv($out, [$titulo, $fallero, 'Fallero/a', $r['dni'], $r['opcion'] ?: 'Sin opción', '-', $r['estado'], ((int)$r['pagada']?'Sí':'No'), ((int)$r['qr_usado']?'Sí':'No'), $r['fecha_reserva']], ';');
    foreach (($invitadosPorReserva[(int)$r['id']] ?? []) as $inv) {
        fputcsv($out, [$titulo, $inv['nombre'], 'Invitado/a '.($inv['tipo'] ?: ''), '', $inv['opcion'] ?: 'Sin opción', $fallero, $r['estado'], ((int)$r['pagada']?'Sí':'No'), ((int)$r['qr_usado']?'Sí':'No'), $r['fecha_reserva']], ';');
    }
}
