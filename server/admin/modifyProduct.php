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

// Obtener datos del formulario de modificación
$itemId = $_POST['itemId'];

// Buscar el item en la colección
$productos = $productsCollection->find();
foreach ($productos as $producto) {
    foreach ($producto['items'] as $item) {
        if ($item['id'] == $itemId) {
            $selectedItem = $item;
            break 2; // Salir de ambos bucles
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <script src="add_delete_detail.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../public/styles.css">
    <title>Modificar Item</title>
</head>
<body>
    <h1>Modificar Item</h1>

    <?php if ($selectedItem): ?>
        <form action="save_changes_product.php" method="post">
            <input type="hidden" name="itemId" value="<?= $itemId ?>">
            <label for="itemName">Nombre del Item:</label>
            <input type="text" id="itemName" name="itemName" value="<?= $selectedItem['name'] ?>" required>
            
            <h3>Detalles del Item:</h3>
            <ul id="detailsList">
                <li> 
                    <label for="itemQuantity">Quantity:</label> 
                    <input type="text" id="itemQuantity" name="itemQuantity" value="<?= $selectedItem['quantity'] ?>" required>
                </li>
                <br><br>
                <?php foreach ($selectedItem['details'] as $index => $detail): ?>
                    <li id="detailItem_<?= $index ?>">
                        <label for="detailName<?= $index ?>">Detail Name:</label>
                        <input type="text" id="detailName<?= $index ?>" name="detailName[]" value="<?= $detail['detail_name'] ?>" required>
                        <br><br>
                        <label for="detailValue<?= $index ?>">Detail Value:</label>
                        <input type="text" id="detailValue<?= $index ?>" name="detailValue[]" value="<?= $detail['detail_value'] ?>" required>
                        <button type="button" class="deleteDetailBtn" data-index="<?= $index ?> "onclick="deleteDetail(this)">Delete</button>
                        <br><br><br><br>
                    </li>
                <?php endforeach; ?>
            </ul>
            <button type="button" onclick="addDetail()">Add Detail</button>
            <button type="submit">Save</button>
        </form>
    <?php else: ?>
        <p>Item no encontrado.</p>
    <?php endif; ?>
    <br>
    <a href="manage_store.php">Volver a la Gestión de Almacén</a>
</body>
</html>
