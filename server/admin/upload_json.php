<?php
session_start();

if (!isset($_SESSION['userId']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php"); 
    exit();
}
if ($_FILES && $_FILES['json_file']['error'] === UPLOAD_ERR_OK) {

    $tmpFilePath = $_FILES['json_file']['tmp_name'];

    $jsonContent = file_get_contents($tmpFilePath);

    $jsonData = json_decode($jsonContent, true);

    if ($jsonData !== null) {
        include_once '../mongodbconnect.php';

        $collection = $dataBase->Products;

        $collection->insertMany($jsonData);

        echo "The data has been successfully loaded into the database.";
    
    } else {
        echo "Error: Could not decode JSON file.";
    }
} else {
    echo "Error loading JSON file.";
}
echo '<p><a href="admin_page.php">Back to admin page</a></p>';
?>