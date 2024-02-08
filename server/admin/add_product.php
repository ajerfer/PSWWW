<?php
session_start();
include_once '../mongodbconnect.php';

if (!isset($_SESSION['userId']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

include_once '../mongodbconnect.php';

$productsCollection = $dataBase->Products;

$uniqueCategories = $productsCollection->distinct('categories', [],['id', 'category_name']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="add_delete_detail.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../public/styles.css">
    <title>Add Product</title>
</head>
<body>
    <h1>Add new product</h1>

    
        <form action="save_new_product.php" method="post">
            <label for="itemName">Nombre del Item:</label>
            <input type="text" id="itemName" name="itemName" value="" required>
            <label for="category">Categoría:</label>
            <select name="category" id="category" required>
                <?php foreach ($uniqueCategories as $category): ?>
                    <option value="<?= $category['id'] ?>"><?= $category['category_name'] ?></option>
                <?php endforeach; ?>
            </select>
            <h3>Item Details:</h3>
            <li>
            <label for="itemQuantity">Quantity:</label>
            <input type="text" id="itemQuantity" name="itemQuantity" value="" required>
            </li>
            <ul id="detailsList"></ul>
            <button type="button" onclick="addDetail()">Add Detail</button>
            <button type="submit">Save</button>
        </form>
    <br>
    <a href="manage_store.php">Back to manage store</a>
</body>
</html>
