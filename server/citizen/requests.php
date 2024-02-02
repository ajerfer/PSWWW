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

// Fetch documents from the collection
$documentP = $productsC->findOne([]);

// Array to store product names
$productsNames = [];
foreach ($documentP['items'] as $item) {
    $productsNames[] = $item['name'];
}
// Delete the empty strings
$productsNames = array_filter($productsNames, function ($value) {
    return strlen($value) > 0;
});

// Save the userId and its associated document
$userId = "3"; // $_SESSION['userId'];
$documentR = $requestsC->findOne(['userId' => $userId]);

// Divide the requests into 'active' and 'completed' categories
$requests = ['active' => [], 'completed' => []];
if ($documentR) {
    foreach ($documentR['requests'] as $request) {
        $category = ($request['state'] == 2) ? 'completed' : 'active';
        $requests[$category][] = $request;
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

        .request-box {
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

    <h1>My Requests</h1>
    
    <div>
        <button onclick="openPopupBox('newRequest')" style="margin-bottom: 20px;">Create Request</button>
    </div>
    <!-- Section Buttons  (the style stills when the webpage is reloaded) -->
    <button class="seccionButton" onclick="showSection('active')" style="<?= ($section === 'active') ? 'background-color:#333; color:white;' : '' ?>">ACTIVE REQUESTS</button>
    <button class="seccionButton" onclick="showSection('completed')" style="<?= ($section === 'completed') ? 'background-color:#333; color:white;' : '' ?>">COMPLETED REQUESTS</button>
    
    <div id="requestsContent">
        <?php foreach ($requests[$section] as $request): ?>    
            <!-- Request Box -->
            <div class="request-box"> 
                <h3>Request <?= dateFormat($request['dateCreated']) ?></h3>
                <ul>
                    <!-- Show the products requested -->
                    <li>Products requested: </li>
                    <ul>
                    <?php for ($i = 0; $i < count($request['products']); $i++): ?>
                            <li><?= $request['products'][$i] ?></li>
                    <?php endfor; ?>
                    </ul> 
                    <li>Persons: <?= $request['nPersons']?> </li>   
                    <!-- Show the state and the corresponding dates -->
                    <?php if ($request['state'] == 0): ?>
                        <li>State: ACTIVE</li> 
                    <?php elseif ($request['state'] == 1): ?>
                        <li>State: ACCEPTED</li>
                        <li>Date Accepted: <?= dateFormat($request['dateAccepted']) ?> </li>
                    <?php else: ?>
                        <li>State: COMPLETED</li>
                        <li>Date Accepted: <?= dateFormat($request['dateAccepted']) ?> </li>
                        <li>Date Completed: <?= dateFormat($request['dateCompleted']) ?> </li>
                    <?php endif; ?>
                </ul>
                <!-- Delete Button if is active-->
                <?php if ($section=="active"): ?>
                    <button onclick="callDeleteRequest('<?= $userId ?>', '<?= $request['id'] ?>')">Delete</button>
                <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <!-- New Request Popup Box -->
    <div id="modalnewRequest" class="modal">
        <div class="modal-content">
            <h2>New Request</h2>
            <label for="persons_input_modal">Persons :</label>
            <input type="number" name="persons_input_modal" class="validity" min="1" value="1" style="margin-bottom: 10px;">
            <div class="input-container">
                <label for="productDropdown">Add a product: </label>
                <select id="productDropdown">
                    <?php foreach ($productsNames as $productName): ?>
                        <option value="<?= $productName ?>"><?= $productName ?></option>
                    <?php endforeach; ?>
                </select>
                <button onclick="addProduct()" style="margin-top:10px">Add Product</button>
            </div>
            <div id="selectedProductsContainer"></div>
            <div class="btn-container">
                <button onclick="closePopupBox('newRequest')">Close</button>
                <button onclick="callCreateRequest(<?= $userId ?>)">Create</button>
            </div>
        </div>
    </div>
    
    <script>
        
    </script>
</body>
</html>
