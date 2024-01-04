<?php
session_start();

// Verificar si el usuario ha iniciado sesión y es un administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../index.php"); // Redirigir a la página de inicio de sesión
    exit();
}
?>
