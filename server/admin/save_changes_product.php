<?php
session_start();
include_once '../mongodbconnect.php';

if (!isset($_SESSION['userId']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$productsCollection = $dataBase->Products;

$itemId = $_POST['itemId'];
$newName = $_POST['itemName'];
$newQuantity = $_POST['itemQuantity'];
$newDetails = isset($_POST['detailName']) ? $_POST['detailName'] : [];
$newValues = isset($_POST['detailValue']) ? $_POST['detailValue'] : [];

$filter = ['items.id' => $itemId];
$update = [
    '$set' => [
        'items.$.name' => $newName,
        'items.$.quantity' => $newQuantity,
        'items.$.details' => [],
    ],
];

foreach ($newDetails as $index => $newDetail) {
    $update['$set']['items.$.details'][$index] = [
        'detail_name' => $newDetail,
        'detail_value' => $newValues[$index],
    ];
}

$result = $productsCollection->updateOne($filter, $update);

if ($result->getModifiedCount() > 0) {
    echo "Changes saved successfully.";
} else {
    echo "No changes were made.";
    
}
echo '<br><br><a href="manage_store.php">Back to manage the store</a>';
exit();
?>
