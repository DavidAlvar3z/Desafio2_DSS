<?php
// classes/Usuario.php
require_once __DIR__ . '/../config/config.php';

class Usuario {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Método para registrar un usuario
    public function registrar($nombre, $email, $password) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (nombre, email, password_hash) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nombre, $email, $password_hash]);
    }

    // Método para hacer login (comparación de la contraseña ingresada contra el hash almacenado)
    public function login($email, $password) {
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();
        if($usuario && password_verify($password, $usuario['password_hash'])){
            return $usuario;
        }
        return false;
    }

    // Recuperar datos de un usuario por su ID
    public function getById($id) {
        $sql = "SELECT * FROM usuarios WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
?>
