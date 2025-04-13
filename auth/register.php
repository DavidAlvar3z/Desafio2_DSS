<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Usuario.php';
require_once __DIR__ . '/../utils/validaciones.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if (empty($nombre) || empty($email) || empty($password)) {
        $error = "⚠️ Todos los campos son requeridos.";
    } elseif (!validarEmail($email)) {
        $error = "❌ El correo no es válido.";
    } elseif (!validarPassword($password)) {
        $error = "🔒 La contraseña debe tener al menos 8 caracteres, incluyendo letras y números.";
    } elseif ($password !== $confirmPassword) {
        $error = "❗ Las contraseñas no coinciden.";
    }

    if (empty($error)) {
        $usuario = new Usuario($pdo);
        if ($usuario->registrar($nombre, $email, $password)) {
            header("Location: login.php");
            exit;
        } else {
            $error = "🚫 Error al registrar el usuario. Intenta nuevamente.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
            <h3 class="text-center mb-4">Registro</h3>

            <?php if (!empty($error)) : ?>
                <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                    <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <input type="text" class="form-control" name="nombre" placeholder="Nombre completo" required>
                </div>
                <div class="mb-3">
                    <input type="email" class="form-control" name="email" placeholder="Correo electrónico" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="password" placeholder="Contraseña" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="confirm_password" placeholder="Confirmar contraseña" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Registrarse</button>
            </form>

            <p class="text-center mt-3">
                ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
