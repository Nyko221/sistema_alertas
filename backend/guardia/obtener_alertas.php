<?php
// filepath: backend/guardia/obtener_alertas.php

require_once '../api/config.php';

$conn = getDBConnection();

// Filtros opcionales
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
$estado = isset($_GET['estado']) ? $_GET['estado'] : '';
$desdeId = isset($_GET['desde_id']) ? intval($_GET['desde_id']) : 0;

$sql = "SELECT * FROM alertas WHERE 1=1";

if ($tipo) {
    $sql .= " AND tipo = '" . $conn->real_escape_string($tipo) . "'";
}

if ($estado) {
    $sql .= " AND estado = '" . $conn->real_escape_string($estado) . "'";
}

// Solo obtener alertas nuevas (con ID mayor al último conocido)
if ($desdeId > 0) {
    $sql .= " AND id > " . $desdeId;
}

$sql .= " ORDER BY fecha_hora DESC";

$result = $conn->query($sql);

$alertas = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $alertas[] = $row;
    }
}

jsonResponse(true, 'Alertas obtenidas correctamente', $alertas);

$conn->close();
?>