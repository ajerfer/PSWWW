<?php
session_start();

// Verificar si el usuario ha iniciado sesión y es un administrador
if (!isset($_SESSION['userId']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php"); // Redirigir a la página de inicio de sesión
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Administrador</title>
    <link rel="stylesheet" type="text/css" href="../public/styles.css">
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        .container {
            position: relative;
            padding-top: 50px; /* Ajusta según sea necesario */
        }

        .logout-btn {
            position: absolute;
            top: 10px; /* Ajusta según sea necesario */
            right: 10px; /* Ajusta según sea necesario */
        }
        .manage_store-btn{
            display: inline-block;
            padding: 20px 40px;
            font-size: 24px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            background-color: #008CBA;
            color: white;
            border: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container"> 
        <h1>Bienvenido a la Página de Administrador</h1>

        <!-- Contenido adicional de la página de administrador -->
        <p>Este es el contenido de la página de administrador.</p>
        <!-- Botón de Logout -->
        <form class="logout-btn" action="../logout.php" method="post">
            <input type="submit" value="Logout">
        </form>

         <!-- Botón de Gestión de Almacén -->
         <form action="manage_store.php" method="post">
            <input type="submit" class="manage-btn" value="Manage Store">
        </form>

       
    </div>
</body>
</html>
