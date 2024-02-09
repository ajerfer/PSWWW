<?php
session_start();

// Verify the role and log in of the user
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'citizen') {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Ciudadano</title>
    <link rel="stylesheet" type="text/css" href="../public/styles.css">
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        .container {
            position: relative;
            padding-top: 50px; 
        }

        .logout-btn {
            position: absolute;
            top: 10px; 
            right: 10px; 
        }
    </style>
</head>
<body>
<div class="container">
        <form class="logout-btn" action="../logout.php" method="post">
            <input type="submit" value="Logout">
        </form>

        <h1>Bienvenido a la Página de Ciudadano</h1>

        <p>Este es el contenido de la página de ciudadano</p>
    </div>
</body>
</html>
