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

// Obtener datos del formulario
$itemId = $_POST['itemId'];
$newName = $_POST['itemName'];
$newQuantity = $_POST['itemQuantity'];
$newDetails = isset($_POST['detailName']) ? $_POST['detailName'] : [];
$newValues = isset($_POST['detailValue']) ? $_POST['detailValue'] : [];
// Buscar el item en la colección
$filter = ['items.id' => $itemId];
$update = [
    '$set' => [
        'items.$.name' => $newName,
        'items.$.quantity' => $newQuantity,
        'items.$.details' => [],
    ],
];

// Agregar los nuevos detalles al array
foreach ($newDetails as $index => $newDetail) {
    $update['$set']['items.$.details'][$index] = [
        'detail_name' => $newDetail,
        'detail_value' => $newValues[$index],
    ];
}

// Actualizar el documento en la base de datos
$result = $productsCollection->updateOne($filter, $update);

if ($result->getModifiedCount() > 0) {
    echo "Changes saved successfully.";
} else {
    echo "No changes were made.";
    
}
echo '<br><br><a href="manage_store.php">Come back to manage the store</a>';
exit();
?>
