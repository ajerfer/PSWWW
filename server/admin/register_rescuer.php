<?php
session_start();

include_once "../databaseconnect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Take data from form
    $username = $_POST["username"];
    $password = $_POST["password"];
    $name = $_POST["name"];
    $surname = $_POST["surname"];
    $lat = $_POST["lat"];
    $lng = $_POST["lng"];

    $con = connect();

    // Verify the conncetion
    if ($con->connect_error) {
        die("Fatal connection: " . $con->connect_error);
    }

    // Insert data into the database
    $queryUser = "INSERT INTO Users (username, password, role, lat, lng) 
              VALUES ('$username', '$password', 'rescuer',$lat,$lng)";
    mysqli_query($con, $queryUser);

    // Obtain the userId
    $userId = mysqli_insert_id($con);

    // Insert data in the table "citizen"
    $queryRescuer = "INSERT INTO Rescuers (userId, name, surname)
                 VALUES ('$userId', '$name', '$surname')";
    mysqli_query($con, $queryRescuer);

    // Verify the insertion
    if(mysqli_affected_rows($con) > 0) {
        header("Location: admin_page.php");
        exit();
    } else {
        echo "Insertion error:" . mysqli_error($con);
    }
    // Close the connection
    $conn->close();
} else {
    header("Location: new_rescuer.php");
    exit();
}
?>
