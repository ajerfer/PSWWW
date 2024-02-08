<?php
session_start();

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
    <title>Admin PAge</title>
    <link rel="stylesheet" type="text/css" href="../../public/styles.css">
</head>
<body>
    
    <header>
        <h1>Welcome to the admin page</h1>
        <form id="logoutBtn" action="../logout.php" method="post">
            <input type="submit" value="Logout">
        </form>
    </header> 
        <form action="manage_store.php" method="post">
            <input type="submit" class="manage-btn" value="Manage Store">
        </form>
        
        <form action="new_rescuer.php" method="post">
            <input type="submit" value="New rescuer">
        </form>
        <form action="statistics.php" method="post">
            <input type="submit" value="Statistics">
        </form>
        <form action="addJsonStore.php" method="post">
            <input type="submit" value="add Json Store">
        </form>
</body>
</html>
