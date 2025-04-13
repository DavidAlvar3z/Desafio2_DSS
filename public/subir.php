<?php
// public/subir.php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Archivo.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/validaciones.php';

mb_internal_encoding("UTF-8"); // Soporte para tildes y UTF-8

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

        if (!in_array($fileExtension, $allowedExtensions)) {
            $error = "❌ Solo se permiten archivos PDF, DOCX o TXT.";
        }

        if (!validarNombreArchivo($fileName)) {
            $error = "❌ El nombre del archivo contiene caracteres no válidos.";
        }

        if (empty($error)) {
            $uploadDir = __DIR__ . "/../uploads/" . $user['id'];
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $safeFileName = pathinfo($fileName, PATHINFO_FILENAME);
            // Permitimos letras, números, guiones, guión bajo, tildes, ñ y espacios
            $safeFileName = preg_replace('/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ_\- ]/u', '_', $safeFileName);
            $safeFileName = mb_strtolower($safeFileName);
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 animate__animated animate__fadeInUp">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0">📤 Subir Nuevo Documento</h4>
        </div>
        <div class="card-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php elseif (!empty($success)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <form method="POST" action="" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Selecciona un archivo</label>
                    <input type="file" name="archivo" class="form-control" accept=".pdf,.docx,.txt" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">🚀 Subir</button>
                </div>
            </form>

            <div class="mt-4 text-center">
                <a href="dashboard.php" class="btn btn-outline-secondary">← Volver al Dashboard</a>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</body>
</html>
