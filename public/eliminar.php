<?php
// public/eliminar.php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Archivo.php';
require_once __DIR__ . '/../config/config.php';

if(!Auth::isLogged()){
    header("Location: ../auth/login.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $archivo_id = intval($_POST['archivo_id']);
    $archivoObj = new Archivo($pdo);
    if($archivoObj->eliminar($archivo_id, Auth::user()['id'])){
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Error eliminando el archivo.";
    }
}
?>
