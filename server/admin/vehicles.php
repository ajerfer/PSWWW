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
include_once "../databaseconnect.php";

$cursor = $vehiclesC->find();

function userName($userId) {
    $con = connect();
    $query = "SELECT username FROM Users WHERE userId = '$userId'";
    $result = mysqli_query($con, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['username'];
    } else {
        return "Error"; 
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- <script src="vehicles.js"></script> -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../../public/styles.css">
    <title>All Vehicles</title>

    <style>
        .vehicle-box {
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

<h1>All Vehicles</h1>

<div id="storageContent">

    <?php foreach ($cursor as $vehicle): ?>
        <!-- Vehicle Box -->
        <div class="vehicle-box">
            <h2> Vehicle of <?= userName($vehicle['userId']) ?> [id=<?= $vehicle['userId'] ?>]</h2>
            <ul>
            <?php foreach ($vehicle['load'] as $item): ?>
                <li><?= $item['name']; ?> </Li>
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
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>
</div>

<script>

</script>
</body>
</html>
