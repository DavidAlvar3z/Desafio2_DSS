<?php
// public/subir.php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Archivo.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/validaciones.php';

if (!Auth::isLogged()) {
    header("Location: ../auth/login.php");
    exit;
}

$user = Auth::user();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['archivo']['tmp_name'];
        $fileName = $_FILES['archivo']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExtensions = ['pdf', 'docx', 'txt'];

        // Validar extensión permitida
        if (!in_array($fileExtension, $allowedExtensions)) {
            $error = "❌ Solo se permiten archivos PDF, DOCX o TXT.";
        }

        // Validar nombre con expresión regular
        if (!validarNombreArchivo($fileName)) {
            $error = "❌ El nombre del archivo contiene caracteres no válidos.";
        }

        if (empty($error)) {
            // Crear carpeta de usuario
            $uploadDir = __DIR__ . "/../uploads/" . $user['id'];
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Evitar sobrescritura agregando un timestamp
            $safeFileName = pathinfo($fileName, PATHINFO_FILENAME);
            $safeFileName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $safeFileName);
            $finalFileName = $safeFileName . "_" . time() . "." . $fileExtension;
            $destPath = $uploadDir . "/" . $finalFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $archivoObj = new Archivo($pdo);
                if ($archivoObj->subir($user['id'], $fileName, $destPath, $fileExtension)) {
                    $success = "✅ Archivo subido correctamente.";
                } else {
                    $error = "❌ Error al guardar en la base de datos.";
                }
            } else {
                $error = "❌ No se pudo mover el archivo.";
            }
        }
    } else {
        $error = "❌ No se recibió ningún archivo válido.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir Documento</title>
</head>
<body>
    <h2>Subir Nuevo Documento</h2>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php elseif (!empty($success)): ?>
        <p style="color:green;"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
    <input type="file" name="archivo" accept=".pdf,.docx,.txt" required><br>
    <button type="submit">Subir</button>
    </form>

    <p><a href="dashboard.php">Volver a Dashboard</a></p>
</body>
</html>