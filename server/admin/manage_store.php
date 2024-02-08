<?php
session_start();

if (!isset($_SESSION['userId']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php"); 
    exit();
}

include_once '../mongodbconnect.php';

$warehouseDoc = $productsC->findOne([]); 

$items = $warehouseDoc['items'];
$categories = $warehouseDoc['categories'];


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="manage_store.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../public/styles.css">
    <title>Products</title>
    <style>
        body {
            margin-top: 10px; /* Espacio para el botón fijo */
        }
        .backBtn {
            position: absolute;
            top: 10px;
            left: 10px;
        }
        .logout-btn {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .addProductBtn {
            position: absolute;
            top: 10px;
            right: 70px;
        }
        .filter-box {
            border: 1px solid #ccc;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .item-box {
            border: 2px solid #ccc;
            padding-top: 10px;
            padding-bottom: 30px;
            padding-left: 30px;
            padding-right: 30px;
            margin: 0px;
            margin-bottom: 10px;
            margin-top: 10px;
            text-align: left;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.7);
        }
        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
        }
    </style>
</head>
<body>
    <form id="backBtn" action="admin_page.php" method="post">
        <button type="submit">Back</button>
    </form>
    <form class="logout-btn" action="../logout.php" method="post">
        <input type="submit" value="Logout">
    </form>
    <form class="addProductBtn" action="add_product.php" method="post">
        <button type="submit">Add Product</button>
    </form>
    
    <h1>List of Products</h1>
    
    <div class="filter-box">
        <b>Filter by Category</b>
        <button id="buttonToggleCategories" style="magin-left: 10px;" onclick="toggleCategories()">Show Categories</button>
        <div id="categories" style="display: none;">
            <div>
                <button style="margin-left: 10px" onclick="selectAllFilters()">All</button>
                <button onclick="clearAllFilters()">Clear</button>
            </div>
            <?php foreach ($categories as $category): ?>
                <input type="checkbox" class="category-checkbox" id="cat_<?= $category['id'] ?>" checked onclick="handleCategoryFilter('<?= $category['id'] ?>')">
                <label for="<?= $category['id'] ?>"><?= $category['category_name'] ?></label>
            <?php endforeach; ?>
        </div>
    </div>

    <?php foreach ($items as $item): ?>
        <div class="item-box" data-category="<?= $item['category'] ?>">
            <h4><?= $item['name'] ?> </h4>
            <ul>
                <li>Quantity: <?= $item['quantity']; ?></li>
                <?php if (!empty($item['details'])): ?>
                    <li>Details</li>
                    <ul>
                        <?php foreach ($item['details'] as $detail): ?>
                            <li><?= $detail['detail_name'] . ': ' . $detail['detail_value']; ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </ul>
            <div>
            <form action="modifyProduct.php" method="post" style="display: inline;">
                <input type="hidden" name="itemId" value="<?= $item['id']; ?>">
                <button type="submit">Modify Product</button>
            </form>
            <form action="deleteProduct.php" method="post" style="display: inline;">
                <input type="hidden" name="itemId" value="<?= $item['id']; ?>">
                <button type="submit">Delete Product</button>
            </form>
        </div>

        </div>
    <?php endforeach; ?>
</body>
</html>
