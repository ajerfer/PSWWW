<?php
session_start();

include_once "../databaseconnect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar datos del formulario
    $username = $_POST["username"];
    $password = $_POST["password"]; // Hash de la contraseña
    $name = $_POST["name"];
    $surname = $_POST["surname"];
    $lat = $_POST["lat"];
    $lng = $_POST["lng"];

    $con = connect();

    // Verificar la conexión
    if ($con->connect_error) {
        die("Fatal connection: " . $con->connect_error);
    }

    // Insertar datos en la base de datos
    $queryUser = "INSERT INTO Users (username, password, role, lat, lng) 
              VALUES ('$username', '$password', 'rescuer',$lat,$lng)";
    mysqli_query($con, $queryUser);

    // Obtener el ID del usuario recién insertado
    $userId = mysqli_insert_id($con);

    // Insertar datos en la tabla Citizen
    $queryRescuer = "INSERT INTO Rescuers (userId, name, surname)
                 VALUES ('$userId', '$name', '$surname')";
    mysqli_query($con, $queryRescuer);

    // Verificar si la inserción fue exitosa
    if(mysqli_affected_rows($con) > 0) {
        // Redirigir al usuario de vuelta a la página de inicio de sesión
        header("Location: admin_page.php");
        exit();
    } else {
        echo "Error en la inserción: " . mysqli_error($con);
    }
    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    // Redirigir si se accede directamente a este script sin enviar datos por POST
    header("Location: new_rescuer.php");
    exit();
}
?>
