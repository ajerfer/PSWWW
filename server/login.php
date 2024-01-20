<?php
session_start();

include_once "databaseconnect.php";

// Obtener datos del formulario
$username = $_POST['username'];
$password = $_POST['password'];
$con = connect();
// Consultar la base de datos para verificar las credenciales
$query = "SELECT * FROM Users WHERE username = '$username' AND password = '$password'";
$result = mysqli_query($con, $query);

if ($result) {
    // Verificar si se encontró un usuario con las credenciales proporcionadas
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        // Guardar información del usuario en la sesión
        $_SESSION['userId'] = $user['userId'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redireccionar a la página correspondiente según el tipo de usuario
        switch ($_SESSION['role']) {
            case 'admin':
                header("Location: admin/admin_page.php");
                break;
            case 'citizen':
                header("Location: citizen/citizen_page.php");
                break;
            case 'rescuer':
                header("Location: rescuer/rescuer_page.php");
                break;
            default:
                // Manejar cualquier otro tipo de usuario según sea necesario
                break;
        }
    } else {
        echo "Credenciales incorrectas. Vuelva a intentarlo.";
    }
} else {
    echo "Error al realizar la consulta.";
}

// Cerrar conexión
mysqli_close($con);
?>
