<?php
session_start();
include_once '../mongodbconnect.php';

// Verificar si el usuario ha iniciado sesión y es un administrador
if (!isset($_SESSION['userId']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php"); // Redirigir a la página de inicio de sesión
    exit();
}

/// Seleccionar la colección de productos
$productsCollection = $dataBase->Products;

// Obtener datos del formulario de modificación
$itemId = $_POST['itemId'];

// Imprimir el valor para verificar que sea correcto
var_dump($itemId);

// Definir el filtro para eliminar el documento con el ID proporcionado
$filter = ['items.id' => $itemId];

// Definir la actualización utilizando $pull para eliminar el elemento del array
$update = ['$pull' => ['items' => ['id' => $itemId]]];

// Eliminar el documento de la colección
$result = $productsCollection->updateOne($filter, $update);

if ($result->getModifiedCount() > 0) {
    echo "Producto eliminado correctamente.";
} else {
    echo "Error al eliminar el producto.";
}

// Redir
header("Location: manage_store.php");
exit();
?>