<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Usuario.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../utils/validaciones.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "⚠️ Todos los campos son obligatorios.";
    } elseif (!validarEmail($email)) {
        $error = "❌ El correo no es válido.";
    }

    if (empty($error)) {
        $usuarioObj = new Usuario($pdo);
        $usuario = $usuarioObj->login($email, $password);
        if ($usuario) {
            Auth::login($usuario);
            header("Location: ../public/dashboard.php");
            exit;
        } else {
            $error = "🚫 Credenciales incorrectas.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
            <h3 class="text-center mb-4">Iniciar Sesión</h3>

            <?php if (!empty($error)) : ?>
                <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                    <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <input type="email" class="form-control" name="email" placeholder="Correo electrónico" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="password" placeholder="Contraseña" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>
            <p class="text-center mt-3">
                ¿No tienes cuenta? <a href="register.php">Regístrate</a>
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
