<?php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Archivo.php';
require_once __DIR__ . '/../config/config.php';

if (!Auth::isLogged()) {
    header("Location: ../auth/login.php");
    exit;
}

$user = Auth::user();
$archivoObj = new Archivo($pdo);
$documentos = $archivoObj->listarPorUsuario($user['id']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Documentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
    <div class="container">
        <span class="navbar-brand">📁 Documentos</span>
        <div class="ms-auto">
            <a href="../auth/logout.php" class="btn btn-outline-light">Cerrar sesión</a>
        </div>
    </div>
</nav>

<div class="container mt-5 animate__animated animate__fadeIn">
    <h2 class="fw-bold text-center mb-4">Bienvenido, <span class="text-primary"><?php echo htmlspecialchars($user['nombre']); ?></span></h2>

    <div class="card border-0 shadow-lg mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">📄 Mis Documentos</h4>
        </div>
        <div class="card-body">
            <?php if (count($documentos) > 0): ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($documentos as $doc): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong><?php echo htmlspecialchars($doc['nombre']); ?></strong><br>
                                <span class="badge bg-secondary"><?php echo $doc['fecha_subida']; ?></span>
                            </div>
                            <form method="POST" action="eliminar.php" class="d-inline">
                                <input type="hidden" name="archivo_id" value="<?php echo $doc['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">🗑 Eliminar</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div class="alert alert-info">No tienes documentos subidos.</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="text-end">
        <a href="subir.php" class="btn btn-success btn-lg">➕ Subir nuevo documento</a>
    </div>
</div>

</body>
</html>
