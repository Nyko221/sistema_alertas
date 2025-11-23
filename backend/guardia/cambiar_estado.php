<?php
// filepath: backend/guardia/cambiar_estado.php

require_once '../api/config.php';

$data = json_decode(file_get_contents('php://input'), true);

$id = isset($data['id']) ? intval($data['id']) : 0;
$estado = isset($data['estado']) ? $data['estado'] : '';

if ($id === 0 || !in_array($estado, ['pendiente', 'atendida', 'rechazada'])) {
    jsonResponse(false, 'Datos inválidos');
}

$conn = getDBConnection();

$stmt = $conn->prepare("UPDATE alertas SET estado = ? WHERE id = ?");
$stmt->bind_param('si', $estado, $id);

if ($stmt->execute()) {
    jsonResponse(true, 'Estado actualizado correctamente');
} else {
    jsonResponse(false, 'Error al actualizar: ' . $stmt->error);
}

$stmt->close();
$conn->close();
?>