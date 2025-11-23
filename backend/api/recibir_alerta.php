<?php
// filepath: backend/api/recibir_alerta.php

require_once 'config.php';

// Solo permitir POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Método no permitido. Use POST.');
}

// Validar datos recibidos
$tipo = isset($_POST['tipo']) ? trim($_POST['tipo']) : '';
$descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';
$latitud = isset($_POST['latitud']) ? floatval($_POST['latitud']) : null;
$longitud = isset($_POST['longitud']) ? floatval($_POST['longitud']) : null;

// Validaciones
if (empty($tipo)) {
    jsonResponse(false, 'El tipo de alerta es obligatorio.');
}

if ($latitud === null || $longitud === null) {
    jsonResponse(false, 'Las coordenadas GPS son obligatorias.');
}

// Procesar foto (si existe)
$nombreFoto = null;
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/uploads/';
    
    // Crear carpeta si no existe
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    // Obtener información del archivo
    $archivoTemporal = $_FILES['foto']['tmp_name'];
    $nombreOriginal = $_FILES['foto']['name'];
    
    // Validar usando getimagesize (más confiable)
    $infoImagen = @getimagesize($archivoTemporal);
    
    if ($infoImagen === false) {
        jsonResponse(false, 'El archivo no es una imagen válida.');
    }
    
    // Validar tipos MIME permitidos
    $allowedMimes = ['image/jpeg', 'image/png', 'image/gif'];
    
    if (!in_array($infoImagen['mime'], $allowedMimes)) {
        jsonResponse(false, 'Tipo no permitido. Solo JPG, PNG o GIF. Recibido: ' . $infoImagen['mime']);
    }
    
    // Validar tamaño (máximo 10MB)
    if ($_FILES['foto']['size'] > 10 * 1024 * 1024) {
        jsonResponse(false, 'La foto es demasiado grande. Máximo 10MB.');
    }
    
    // Obtener extensión correcta del MIME type
    $extensiones = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
    ];
    
    $extension = $extensiones[$infoImagen['mime']];
    
    // Generar nombre único y seguro
    $nombreFoto = 'alerta_' . time() . '_' . uniqid() . '.' . $extension;
    $rutaDestino = $uploadDir . $nombreFoto;
    
    // Mover archivo
    if (!move_uploaded_file($archivoTemporal, $rutaDestino)) {
        jsonResponse(false, 'Error al subir la foto. Verifica permisos de escritura.');
    }
}

// Guardar en base de datos
$conn = getDBConnection();

$stmt = $conn->prepare(
    "INSERT INTO alertas (tipo, descripcion, foto, latitud, longitud) 
     VALUES (?, ?, ?, ?, ?)"
);

$stmt->bind_param('sssdd', $tipo, $descripcion, $nombreFoto, $latitud, $longitud);

if ($stmt->execute()) {
    $alertaId = $stmt->insert_id;
    
    jsonResponse(true, 'Alerta recibida correctamente.', [
        'id' => $alertaId,
        'tipo' => $tipo,
        'descripcion' => $descripcion,
        'foto' => $nombreFoto,
        'latitud' => $latitud,
        'longitud' => $longitud
    ]);
} else {
    jsonResponse(false, 'Error al guardar la alerta: ' . $stmt->error);
}

$stmt->close();
$conn->close();
?>