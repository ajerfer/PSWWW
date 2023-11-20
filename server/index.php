<?php
session_start();

// Verificar si se recibieron datos de inicio de sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verificar las credenciales (simplemente por propósitos demostrativos)
    if ($username === 'usuario' && $password === 'contrasena') {
        $_SESSION['username'] = $username;
        echo json_encode(['success' => true, 'message' => 'Inicio de sesión exitoso']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Credenciales incorrectas']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Solicitud incorrecta']);
}
?>

