<?php
// auth/register.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Usuario.php';
require_once __DIR__ . '/../utils/validaciones.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validaciones
    if(empty($nombre) || empty($email) || empty($password)){
        $error = "Todos los campos son requeridos.";
    } elseif(!validarEmail($email)){
        $error = "El correo no es válido.";
    } elseif(!validarPassword($password)){
        $error = "La contraseña debe tener al menos 8 caracteres, incluyendo letras y números.";
    } elseif($password !== $confirmPassword){
        $error = "Las contraseñas no coinciden.";
    }

    if(isset($error)){
        echo $error;
        exit;
    } else {
        $usuario = new Usuario($pdo);
        if($usuario->registrar($nombre, $email, $password)){
            header("Location: login.php");
            exit;
        } else {
            echo "Error al registrar el usuario.";
            exit;
        }
    }
} else {
    // Formulario de registro
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Registro de Usuario</title>
    </head>
    <body>
        <h2>Registro</h2>
        <form method="POST" action="">
            <input type="text" name="nombre" placeholder="Nombre completo" required><br>
            <input type="email" name="email" placeholder="Correo electrónico" required><br>
            <input type="password" name="password" placeholder="Contraseña" required><br>
            <input type="password" name="confirm_password" placeholder="Confirmar contraseña" required><br>
            <button type="submit">Registrarse</button>
        </form>
        <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
    </body>
    </html>
    <?php
}
?>
