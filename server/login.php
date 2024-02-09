<?php
session_start();

include_once "databaseconnect.php";

$username = $_POST['username'];
$password = $_POST['password'];
$con = connect();

$query = "SELECT * FROM Users WHERE username = '$username' AND password = '$password'";
$result = mysqli_query($con, $query);

if ($result) {
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        $_SESSION['userId'] = $user['userId'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        switch ($_SESSION['role']) {
            case 'admin':
                header("Location: admin/admin_page.php");
                break;
            case 'citizen':
                header("Location: citizen/citizen_page.php");
                break;
            case 'rescuer':
                header("Location: map.php");
                break;
            default:
                break;
        }
    } else {
        echo "Incorrect credentials. Try again.";
    }
} else {
    echo "Error.";
}

// Close connection
mysqli_close($con);
?>
