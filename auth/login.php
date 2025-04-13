<?php
// auth/login.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Usuario.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../utils/validaciones.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if(empty($email) || empty($password)){
        $error = "Todos los campos son obligatorios.";
    } elseif(!validarEmail($email)){
        $error = "El correo no es válido.";
    }

    if(isset($error)){
        echo $error;
        exit;
    } else {
        $usuarioObj = new Usuario($pdo);
        $usuario = $usuarioObj->login($email, $password);
        if($usuario){
            Auth::login($usuario);
            header("Location: ../public/dashboard.php");
            exit;
        } else {
            echo "Credenciales incorrectas.";
            exit;
        }
    }
} else {
    // Formulario de inicio de sesión
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Iniciar Sesión</title>
    </head>
    <body>
        <h2>Login</h2>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Correo electrónico" required><br>
            <input type="password" name="password" placeholder="Contraseña" required><br>
            <button type="submit">Entrar</button>
        </form>
        <p>¿No tienes cuenta? <a href="register.php">Regístrate</a></p>
    </body>
    </html>
    <?php
}
?>
