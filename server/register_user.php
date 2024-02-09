<?php
session_start();

include_once "databaseconnect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];
    $name = $_POST["name"];
    $surname = $_POST["surname"];
    $phone = $_POST["phone"];
    $lat = $_POST["lat"];
    $lng = $_POST["lng"];

    $con = connect();

    if ($con->connect_error) {
        die("Fatal connection: " . $con->connect_error);
    }

    $queryUser = "INSERT INTO Users (username, password, role) 
              VALUES ('$username', '$password', 'citizen')";
    mysqli_query($con, $queryUser);

    $userId = mysqli_insert_id($con);

    $queryCitizen = "INSERT INTO Citizens (userId, name, surname, phone, lat, lng)
                 VALUES ('$userId', '$name', '$surname', '$phone', '$lat', '$lng')";
    mysqli_query($con, $queryCitizen);

    if(mysqli_affected_rows($con) > 0) {
        header("Location: index.php");
        exit();
    } else {
        echo "Insertion error: " . mysqli_error($con);
    }
    $conn->close();
} else {
    // Move the user if they access without doing POST
    header("Location: new_user.php");
    exit();
}
?>

