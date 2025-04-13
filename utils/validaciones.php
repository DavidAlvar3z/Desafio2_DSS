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

// Validación del nombre del archivo (permite letras, números, espacios, guiones, guiones bajos y extensión de 3-4 letras)
function validarNombreArchivo($nombre) {
    return preg_match('/^[\w,\s-]+\.[A-Za-z]{3,4}$/', $nombre);
}

function validarTipoArchivo($nombreArchivo) {
    $extensionesPermitidas = ['pdf', 'docx', 'txt'];
    $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));
    return in_array($extension, $extensionesPermitidas);
}
?>
