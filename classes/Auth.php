<?php
// classes/Auth.php
session_start();

class Auth {
    // Inicia la sesión del usuario y establece una cookie
    public static function login($usuario) {
        $_SESSION['user'] = $usuario;
        setcookie('user', $usuario['email'], time() + (86400 * 30), "/");  // Cookie válida 30 días
    }

    // Destruye la sesión y elimina la cookie
    public static function logout() {
        session_destroy();
        setcookie('user', '', time() - 3600, "/");
    }

    // Verifica si el usuario está autenticado
    public static function isLogged() {
        return isset($_SESSION['user']);
    }

    // Retorna los datos del usuario logueado
    public static function user() {
        return $_SESSION['user'] ?? null;
    }
}
?>
