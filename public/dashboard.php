<?php
// public/dashboard.php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Archivo.php';
require_once __DIR__ . '/../config/config.php';

if(!Auth::isLogged()){
    header("Location: ../auth/login.php");
    exit;
}

$user = Auth::user();
$archivoObj = new Archivo($pdo);
$documentos = $archivoObj->listarPorUsuario($user['id']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h2>Hola, <?php echo htmlspecialchars($user['nombre']); ?></h2>
    <p><a href="../auth/logout.php">Cerrar sesión</a></p>

    <h3>Mis Documentos</h3>
    <?php if(count($documentos) > 0): ?>
        <ul>
            <?php foreach($documentos as $doc): ?>
                <li>
                    <?php echo htmlspecialchars($doc['nombre']); ?> - <?php echo $doc['fecha_subida']; ?>
                    <!-- Formulario para eliminar documento -->
                    <form method="POST" action="eliminar.php" style="display:inline;">
                        <input type="hidden" name="archivo_id" value="<?php echo $doc['id']; ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No tienes documentos subidos.</p>
    <?php endif; ?>
    <p><a href="subir.php">Subir nuevo documento</a></p>
</body>
</html>
