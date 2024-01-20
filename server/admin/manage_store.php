<?php
session_start();
include_once '../mongodbconnect.php';

// Verificar si el usuario ha iniciado sesión y es un administrador
if (!isset($_SESSION['userId']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php"); // Redirigir a la página de inicio de sesión
    exit();
}

// Seleccionar la colección de productos
$productsCollection = $dataBase->Products;

// Recuperar todos los documentos de la colección
$productos = $productsCollection->find();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
</head>
<body>
    <h1>Lista de Productos</h1>
    <?php foreach ($productos as $producto): ?>
        <div>
            <ul>
                <?php foreach ($producto['items'] as $item): ?>
                    <li><?= $item['name']; ?></li>
                    <ul>
                        <?php foreach ($item['details'] as $detail): ?>
                                <li><?= $detail['detail_name'] . ': ' . $detail['detail_value']; ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>
</body>
</html>

