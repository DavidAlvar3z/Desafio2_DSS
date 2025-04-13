<?php
// classes/Archivo.php
require_once __DIR__ . '/../config/config.php';

class Archivo {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Registra un archivo en la base de datos
    public function subir($usuario_id, $nombreOriginal, $ruta, $tipo) {
        $sql = "INSERT INTO archivos (usuario_id, nombre, ruta, tipo) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$usuario_id, $nombreOriginal, $ruta, $tipo]);
    }

    // Lista los archivos de un usuario
    public function listarPorUsuario($usuario_id) {
        $sql = "SELECT * FROM archivos WHERE usuario_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll();
    }

    // Lista todos los archivos (para la API REST)
    public function listarTodos() {
        $sql = "SELECT * FROM archivos";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Elimina un archivo tanto del sistema de archivos como de la base de datos
    public function eliminar($archivo_id, $usuario_id) {
        $sql = "SELECT ruta FROM archivos WHERE id = ? AND usuario_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$archivo_id, $usuario_id]);
        $archivo = $stmt->fetch();
        if($archivo){
            if(file_exists($archivo['ruta'])){
                unlink($archivo['ruta']);
            }
            $sql = "DELETE FROM archivos WHERE id = ? AND usuario_id = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$archivo_id, $usuario_id]);
        }
        return false;
    }

    // Actualiza la información de un archivo
    public function obtenerTodos() {
        $stmt = $this->pdo->prepare("SELECT * FROM archivos");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtiene un archivo por su ID
    public function obtenerPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM archivos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }    
}
?>
