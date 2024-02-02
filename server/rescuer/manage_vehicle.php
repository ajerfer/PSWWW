<?php

// Start the session
session_start();

// Verify the user
// if (!isset($_SESSION['userId']) || $_SESSION['role'] !== 'rescuer')) {
//     header("Location: ../index.php"); // Redirect to the login page
//     exit();
// }

// Include MongoDB connection file
include_once "../mongodbconnect.php";

// Save the userId and its associated document
$userId = "4"; // $_SESSION['userId'];
$vehicleDoc = $vehiclesC.findOne(['userId' => $userId]);

$warehouseDoc = $productsC.findOne();

$content = ['vehicle' => $vehicleDoc['load'], 'warehouse' => $warehouseDoc['items']];

// Set the initial value for the section variable
$section = isset($_SESSION['section']) ? $_SESSION['section'] : 'vehicle';

// Change the section based on the POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['section'])) {
    $_SESSION['section'] = $_POST['section'];
    $section = $_POST['section'];
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <script src="manage_vehicle.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../../public/styles.css">
    <title>My Vehicle</title>

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

    </style>
</head>

<body>

    <h1>My Vehicle</h1>

    <!-- Section Buttons  (the style stills when the webpage is reloaded) -->
    <button class="seccionButton" onclick="showSection('vehicle')" style="<?= ($section === 'vehicle') ? 'background-color:#333; color:white;' : '' ?>">VEHICLE STORAGE</button>
    <button class="seccionButton" onclick="showSection('completed')" style="<?= ($section === 'completed') ? 'background-color:#333; color:white;' : '' ?>">WAREHOUSE STORAGE</button>
    
    <div id="storageContent">
        <?php foreach ($content[$section] as $item): ?>    
            <!-- Item Box -->
            <div class="item-box"> 
                <h4><?= $item['name'] ?> </h4>
                <ul>
                    <!-- Show the item details -->
                    <li>Quantity: <?= $item['quantity']; ?></li>
                    <?php foreach ($item['details'] as $detail): ?>
                        <li><?= $detail['detail_name'] . ': ' . $detail['detail_value']; ?></li>
                    <?php endforeach; ?>
                </ul>
                
                <button onclick="openPopUpBox('<?= $item['id'] ?>')"><?= ($section=='vehicle') ? 'Unload' : 'Load'?></button>


                <?php if ($section=="vehicle"):?>
                <?php elseif ($seciton=="warehouse"): ?>
                <?php endif; ?>
                
                <!-- Popup Quantity Box -->
                <div id="modal<?= $item['id'] ?>" class="modal">
                    <div class="modal-content">
                        <label for=">Quantity [1-<?=$item['quantity']?>]</label>
                        <input type="number" id="<?= $item['id'] ?>" class="validity" min="0" max="<?=$item['quantity']?>" value="0">
                        <div class="btn-container">
                            <button onclick="closePopupBox('<?= $announcement['id'] ?>')">Close</button>
                            <button onclick="callManageItem('<?= $userId ?>','<?= $item['id'] ?>', getQuantities('<?= $announcement['id'] ?>', <?= count($announcement['products']) ?>))">Create offer</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <script>
        
    </script>
</body>
</html>
