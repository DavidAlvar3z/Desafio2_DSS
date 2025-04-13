<?php
// classes/Api.php
require_once __DIR__ . '/../classes/Archivo.php';

class Api {
    private $archivo;

    public function __construct($pdo) {
        $this->archivo = new Archivo($pdo);
    }

    // Devuelve en formato JSON la lista de todos los documentos
    public function obtenerDocumentos() {
        $documentos = $this->archivo->listarTodos();
        header('Content-Type: application/json');
        echo json_encode($documentos);
    }
}
?>
