<?php
session_start();

include_once "databaseconnect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar datos del formulario
    $username = $_POST["username"];
    $password = $_POST["password"]; // Hash de la contraseña
    $name = $_POST["name"];
    $surname = $_POST["surname"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];

    $con = connect();

    // Verificar la conexión
    if ($con->connect_error) {
        die("Fatal connection: " . $con->connect_error);
    }

    // Insertar datos en la base de datos
    $queryUser = "INSERT INTO Users (username, password, role) 
              VALUES ('$username', '$password', 'citizen')";
    mysqli_query($con, $queryUser);

    // Obtener el ID del usuario recién insertado
    $userId = mysqli_insert_id($con);

    // Insertar datos en la tabla Citizen
    $queryCitizen = "INSERT INTO Citizens (userId, name, surname, phone, address)
                 VALUES ('$userId', '$name', '$surname', '$phone', '$address')";
    mysqli_query($con, $queryCitizen);

    // Verificar si la inserción fue exitosa
    if(mysqli_affected_rows($con) > 0) {
        // Redirigir al usuario de vuelta a la página de inicio de sesión
        header("Location: index.php");
        exit();
    } else {
        echo "Error en la inserción: " . mysqli_error($con);
    }
    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    // Redirigir si se accede directamente a este script sin enviar datos por POST
    header("Location: new_user.php");
    exit();
}
?>
