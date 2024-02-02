<?php

// Start the session
session_start();

// Verify the user
// if (!isset($_SESSION['userId']) || $_SESSION['role'] !== 'citizen')) {
//     header("Location: ../index.php"); // Redirect to the login page
//     exit();
// }

// Include MongoDB connection file
include_once "../mongodbconnect.php";

// Save the userId and its associated document
$userId = "3"; // $_SESSION['userId'];
$documentR = $requestsC->findOne(['userId' => $userId]);

// Divide the requests into 'active' and 'completed' categories
$requests = ['active' => [], 'completed' => []];
if ($documentR) {
    foreach ($documentR['requests'] as $offer) {
        $category = ($offer['state'] == 2) ? 'completed' : 'active';
        $requests[$category][] = $offer;
    }
}

// Set the initial value for the section variable
$section = isset($_SESSION['section']) ? $_SESSION['section'] : 'active';

// Change the section based on the POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['section'])) {
    $_SESSION['section'] = $_POST['section'];
    $section = $_POST['section'];
}

function dateFormat ($date) {
    return ($date->toDateTime())->format('Y-m-d H:i:s');
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <script src="requests.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../../public/styles.css">
    <title>My Requests</title>

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

        .offer-box {
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

    <h1>My Requests</h1>

    <!-- Section Buttons  (the style stills when the webpage is reloaded) -->
    <button class="seccionButton" onclick="showSection('active')" style="<?= ($section === 'active') ? 'background-color:#333; color:white;' : '' ?>">ACTIVE REQUESTS</button>
    <button class="seccionButton" onclick="showSection('completed')" style="<?= ($section === 'completed') ? 'background-color:#333; color:white;' : '' ?>">COMPLETED REQUESTS</button>
    
    <div id="requestsContent">
        <?php foreach ($requests[$section] as $offer): ?>    
            <!-- Offer Box -->
            <div class="offer-box"> 
                <h3>Offer <?= dateFormat($offer['dateCreated']) ?></h3>
                <ul>
                    <!-- Show the products offered -->
                    <li>Products offered: </li>
                    <ul>
                    <?php for ($i = 0; $i < count($offer['products']); $i++): ?>
                        <!-- ?php if ($offer['nProducts'][$i] != 0): ?> -->
                            <li><?= $offer['products'][$i] ?> (<?= $offer['nProducts'][$i] ?>)</li>
                        <!-- ?php endif; ?> -->
                    <?php endfor; ?>
                    </ul>       
                    <!-- Show the state and the corresponding dates -->
                    <?php if ($offer['state'] == 0): ?>
                        <li>State: ACTIVE</li> 
                    <?php elseif ($offer['state'] == 1): ?>
                        <li>State: ACCEPTED</li>
                        <li>Date Accepted: <?= dateFormat($offer['dateAccepted']) ?> </li>
                    <?php else: ?>
                        <li>State: COMPLETED</li>
                        <li>Date Accepted: <?= dateFormat($offer['dateAccepted']) ?> </li>
                        <li>Date Completed: <?= dateFormat($offer['dateCompleted']) ?> </li>
                    <?php endif; ?>
                </ul>
                <!-- Delete Button if is active-->
                <?php if ($section=="active"): ?>
                    <button onclick="callDeleteOffer('<?= $userId ?>', '<?= $offer['id'] ?>')">Delete</button>
                <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    
    <script>
        
    </script>
</body>
</html>
