<?php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Archivo.php';
require_once __DIR__ . '/../config/config.php';

if (!Auth::isLogged()) {
    header("Location: ../auth/login.php");
    exit;
}

$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $archivo_id = intval($_POST['archivo_id']);
    $archivoObj = new Archivo($pdo);
    
    if ($archivoObj->eliminar($archivo_id, Auth::user()['id'])) {
        header("Location: dashboard.php");
        exit;
    } else {
        $error = true;
    }
}
?>

<?php if ($error): ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Error</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body class="bg-danger text-white d-flex justify-content-center align-items-center vh-100">
    <div class="text-center animate__animated animate__shakeX">
        <h1 class="display-4">❌ Error al eliminar</h1>
        <p class="lead">No se pudo eliminar el archivo. Intenta nuevamente.</p>
        <a href="dashboard.php" class="btn btn-light btn-lg mt-4">← Volver al Dashboard</a>
    </div>
</body>
</html>
<?php endif; ?>
