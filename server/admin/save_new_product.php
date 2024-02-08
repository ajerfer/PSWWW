<?php
session_start();
include_once '../mongodbconnect.php';

if (!isset($_SESSION['userId']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$productsCollection = $dataBase->Products;

$newName = $_POST['itemName'];
$newCategory = $_POST['category'];
$newQuantity = $_POST['itemQuantity'];
$newDetails = isset($_POST['detailName']) ? $_POST['detailName'] : [];
$newValues = isset($_POST['detailValue']) ? $_POST['detailValue'] : [];

$newProduct = [
    'id' => uniqid(),
    'name' => $newName,
    'category' => $newCategory,
    'quantity' => $newQuantity,
    'details' => []
];

if (!empty($newDetails) && !empty($newValues)) {
    for ($i = 0; $i < count($newDetails); $i++) {
        $detailName = $newDetails[$i];
        $detailValue = $newValues[$i];

        $newProduct['details'][] = [
            'detail_name' => $detailName,
            'detail_value' => $detailValue
        ];
    }
}

$firstDocument = $productsCollection->findOne();

$firstDocumentId = $firstDocument['_id'];

$filter = ['_id' => $firstDocumentId];
$result = $productsCollection->updateOne(
    $filter,
    ['$push' => ['items' => $newProduct]]
);

if ($result->getModifiedCount() > 0) {
    echo "Product inserted correctly.";
} else {
    echo "Error.";
}
echo '<br><br><a href="manage_store.php">Back to manage the store</a>';
exit();
?>
