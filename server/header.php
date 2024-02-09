<!-- header.php -->
<?php
if (isset($_SESSION['role'])) {
    // Determinar la URL según el rol
    $url = '';
    switch ($_SESSION['role']) {
        case 'admin':
            $url = 'admin_menu.php';
            break;
        case 'user':
            $url = 'user_menu.php';
            break;
        // Añade más casos según sea necesario para otros roles
        default:
            $url = 'default_menu.php';
            break;
    }
} 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../public/styles.css">
    <title>Título de tu Proyecto</title>
    <!-- Aquí puedes agregar tus estilos CSS comunes -->
    <link rel="stylesheet" href="estilos.css">
    <style>
        .navbar {
            display: flex;
            justify-content: space-between; /* Alinear elementos a los extremos */
            align-items: center; /* Centrar verticalmente los elementos */
        }

        .navbar button {
            margin: 5px; /* Añadir margen entre los botones */
        }
    </style>
</head>
<body>

<!-- Barra de navegación -->
<div class="navbar">
    <button style="margin-left: 10px;" onclick="window.location.href = '<?php echo $url; ?>'">Back Menu</button>
    <button onclick="window.location.href='logout.php'" style="margin-right: 10px;">Log Out</button>
</div>
