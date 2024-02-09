<?php
session_start();
include_once "../header.php";

if (!isset($_SESSION['userId']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page - Load JSON</title>
</head>
<body>
    <h2>Load JSON into the Database</h2>
    <form action="upload_json.php" method="post" enctype="multipart/form-data">
        <input type="file" name="json_file" accept=".json" required>
        <button type="submit">Load JSON</button>
    </form>
</body>
</html>