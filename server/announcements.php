<?php
session_start();

// Verify the user 
if (!isset($_SESSION['userId']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'citizen')) {
    header("Location: ./index.php"); // Redirigir a la página de inicio de sesión
    exit();
} 

// Save the userId 
$userId = $_SESSION['userId'];

include_once "mongodbconnect.php";

// Fetch documents from the collection
$documentP = $productsC->findOne([]);
$documentA = $announcementsC->findOne([]);

// Array to store product names
$products = [];

// Iterate through products and extract names
foreach ($documentP['items'] as $item) {
    $products[] = [$item['id'], $item['name']];
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <script src="announcements.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../public/styles.css">
    <title>Announcements List</title>
    <style>
        body {
            margin: 30px;
        }
        .announcement-box {
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
        .btn-container {
            margin-top: auto;
            margin-top: 20px;
        }
        .input-container {
            margin-bottom: 10px;
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

    <h1>Announcements List</h1>

    <!-- New Announcement Button -->
    <?php if ($_SESSION['role'] === 'admin'): ?>
        <button onclick="openPopupBox('newAnnouncement')">Create Announcement</button>
    <?php endif; ?>

    <!--  Announcement List -->
    <?php
    $announcementNumber = 1; // Initialize the announcement number
    foreach ($documentA['announcements'] as $announcement):
    ?>    
        <!-- Announcement Box -->
        <div class="announcement-box"> 
            <h3>Announcement <?= $announcementNumber ?></h3>
            <ul>
                <?php foreach ($announcement['products'] as $string): ?>
                    <li><?= $string ?></li>
                <?php endforeach; ?>
            </ul>
            <!-- Button - Only for citizen -->
            <?php if ($_SESSION['role'] === 'citizen'): ?>
                <button onclick="openPopupBox('<?= $announcement['id'] ?>')">Make offer</button>
            <?php endif; ?>
            <!-- Button - Only for admin -->
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <button onclick="callDeleteAnnouncement('<?= $announcement['id'] ?>')">Delete</button>
            <?php endif; ?>
        </div>
        <!-- Popup Offer Box -->
        <div id="modal<?= $announcement['id'] ?>" class="modal">
            <div class="modal-content">
                <?php foreach ($announcement['products'] as $index => $product): ?>
                    <p><?= $product ?></p>
                    <input type="number" name="<?= $announcement['id'] ?>_<?= $index ?>_input_modal" class="validity" min="0" value="0">
                <?php endforeach; ?>
                <div class="btn-container">
                    <button onclick="closePopupBox('<?= $announcement['id'] ?>')">Close</button>
                    <button onclick="callCreateOffer('<?= $userId ?>','<?= $announcement['id'] ?>', getQuantities('<?= $announcement['id'] ?>', <?= count($announcement['products']) ?>))">Create offer</button>
                </div>
            </div>
        </div>
        <?php
        $announcementNumber++; // Increment the announcement number
    endforeach;
    ?>
    <!-- New Announcement Popup Box -->
    <div id="modalnewAnnouncement" class="modal">
        <div class="modal-content">
            <h2>New Announcement</h2>
            <div class="input-container">
                <label for="productDropdown">Add a product: </label>
                <select id="productDropdown">
                    <?php foreach ($products as $product): ?>
                        <option value="<?= $product[0] . '|' . $product[1] ?>"><?= $product[1] ?></option>
                    <?php endforeach; ?>
                </select>
                <button onclick="addProduct()" style="margin-top:10px">Add Product</button>
            </div>
            <div id="selectedProductsContainer"></div>
            <div class="btn-container">
                <button onclick="closePopupBox('newAnnouncement')">Close</button>
                <button onclick="callCreateAnnouncement()">Create</button>
            </div>
        </div>
    </div>

    <script>

    </script>

</body>
</html>
