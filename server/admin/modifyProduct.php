<?php
session_start();
include_once '../mongodbconnect.php';

if (!isset($_SESSION['userId']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php"); 
    exit();
}

$productsCollection = $dataBase->Products;

$itemId = $_POST['itemId'];

$products = $productsCollection->find();
foreach ($products as $product) {
    foreach ($product['items'] as $item) {
        if ($item['id'] == $itemId) {
            $selectedItem = $item;
            break 2;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="add_delete_detail.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../public/styles.css">
    <title>Modify Item</title>
</head>
<body>
    <h1>Modify Item</h1>

    <?php if ($selectedItem): ?>
        <form action="save_changes_product.php" method="post">
            <input type="hidden" name="itemId" value="<?= $itemId ?>">
            <label for="itemName">Item name:</label>
            <input type="text" id="itemName" name="itemName" value="<?= $selectedItem['name'] ?>" required>
            
            <h3>Item details:</h3>
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
        <p>Item not found</p>
    <?php endif; ?>
    <br>
    <a href="manage_store.php">Back to manage store</a>
</body>
</html>
