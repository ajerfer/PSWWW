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
$newName = $_POST['itemName'];
$newCategory = $_POST['category'];
$newDetails = $_POST['detailName'];
$newValues = $_POST['detailValue'];

$newProduct = [
    'items' => [
        [
            'id' => uniqid(),
            'name' => $newName,
            'category' => $newCategory,
            'details' => []
        ]
    ]
];

if (!empty($newDetails) && !empty($newValues)) {
    // Iterar sobre los detalles y valores y agregarlos al producto
    for ($i = 0; $i < count($newDetails); $i++) {
        $detailName = $newDetails[$i];
        $detailValue = $newValues[$i];

        $newProduct['items'][0]['details'][] = [
            'detail_name' => $detailName,
            'detail_value' => $detailValue
        ];
    }
}
$result = $productsCollection->insertOne($newProduct);

if ($result->getInsertedCount() > 0) {
    echo "Producto insertado correctamente.";
} else {
    echo "Error al insertar el producto.";
}
echo '<br><br><a href="manage_store.php">Come back to manage the store</a>';
exit();
?>
