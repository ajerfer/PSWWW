<?php

// Start the session
session_start();

// Verify the user
if (!isset($_SESSION['userId']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php"); // Redirect to the login page
    exit();
}

// Include MongoDB connection file
include_once "../mongodbconnect.php";

// Save the userId and its associated document
$userId =  $_SESSION['userId'];


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <!-- <script src="vehicles.js"></script> -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../../public/styles.css">
    <title>All Vehicles</title>

    <style>
        .seccionButton {
            padding: 10px;
            margin-right: 10px;
            cursor: pointer;
        }

        .seccionButton:hover {
            background-color: #333;
            color: white;
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

        .filter-box {
            border: 1px solid #ccc;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<h1>All Vehicles</h1>

<div id="storageContent">

    <div>
        <?php if ($isNear && $section == 'vehicle'): ?>
            <button onclick="callUnloadVehicle(<?= $userId ?>)" style="margin-top: 10px;">Unload Vehicle</button>
        <?php endif; ?>
    </div>

    <!-- Filter section -->
    <?php if ($isNear && $section == 'warehouse'): ?>
        <div class="filter-box">
            <div>
                <b>Filter by Category</b>
                <button style="margin-right: 10px" onclick="selectAllFilters()">All</button>
                <button onclick="clearAllFilters()">Clear</button>
            </div>
            <?php foreach ($categories as $category): ?>
                <input type="checkbox" class="category-checkbox" id="cat_<?= $category['id'] ?>" checked onclick="handleCategoryFilter('<?= $category['id'] ?>')">
                <label for="<?= $category['id'] ?>"><?= $category['category_name'] ?></label>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php foreach ($content[$section] as $item): ?>
        <!-- Item Box -->
        <div class="item-box" data-category="<?= $item['category'] ?>">
            <h4><?= $item['name'] ?> </h4>
            <ul>
                <!-- Show the item details -->
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
            <?php if ($section == 'warehouse'): ?>
                <button onclick=" openPopupBox('<?= $item['id'] ?>');">Load</button>
            <?php endif; ?>
        </div>
        <!-- Popup Quantity Box -->
        <div id="modal<?= $item['id'] ?>" class="modal">
            <div class="modal-content">
                <h4> Quantity [1-<?= $item['quantity'] ?>] </h4>
                <input type="number" id="quantity_input_<?= $item['id'] ?>" class="validity" min="1" max="<?= $item['quantity'] ?>" value="1" style="margin-bottom: 20px;">
                <div class="btn-container">
                    <button onclick="closePopupBox('<?= $item['id'] ?>')">Close</button>
                    <button onclick="callLoadItem('<?= $userId ?>','<?= $item['id'] ?>', getElementById('quantity_input_<?= $item['id'] ?>').value, <?= $item['quantity'] ?>) ">Load</button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>

</script>
</body>
</html>
