<?php
session_start();
include_once '../mongodbconnect.php';

if (!isset($_SESSION['userId']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$productsCollection = $dataBase->Products;


$itemId = $_POST['itemId'];

$filter = ['items.id' => $itemId];

$update = ['$pull' => ['items' => ['id' => $itemId]]];

$result = $productsCollection->updateOne($filter, $update);

if ($result->getModifiedCount() > 0) {
    echo "Product deleted";
} else {
    echo "Error";
}

header("Location: manage_store.php");
exit();
?>
