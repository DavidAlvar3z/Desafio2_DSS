<?php
// utils/validaciones.php

// Validación de correo electrónico
function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Validación de contraseña (mínimo 8 caracteres, al menos una letra y un número)
function validarPassword($password) {
    return preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password);
}

// Validación del nombre del archivo (acepta letras, números, símbolos comunes, espacios y tildes)
function validarNombreArchivo($nombre) {
    // Aceptamos cualquier caracter Unicode excepto caracteres de control y de ruta como / \ :
    return preg_match('/^[^\/:*?"<>|\\\\]{1,255}$/u', $nombre);
}

// Validación de tipo de archivo permitido
function validarTipoArchivo($nombreArchivo) {
    $extensionesPermitidas = ['pdf', 'docx', 'txt'];
    $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));
    return in_array($extension, $extensionesPermitidas);
}
?>
