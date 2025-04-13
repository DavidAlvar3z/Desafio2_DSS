<?php
// api/documentos.php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Archivo.php';

header('Content-Type: application/json');

$archivo = new Archivo($pdo);

// Si se pasa un ID por GET, obtenemos un solo archivo
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $documento = $archivo->obtenerPorId($id);

    if ($documento) {
        echo json_encode($documento);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Documento no encontrado']);
    }
    exit;
}

// Si no hay ID, se devuelven todos los documentos
$documentos = $archivo->obtenerTodos();
echo json_encode($documentos);
?>