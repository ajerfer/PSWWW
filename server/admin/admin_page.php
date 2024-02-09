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
    <title>Admin Menu</title>
    <link rel="stylesheet" type="text/css" href="../../public/styles.css">
    <style>
        body {
            background-color: #f5f5f5;
            color: #333;
            font-family: Arial, sans-serif;
            margin: 0;
        }

        header {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding:    ;
        }

        h1 {
            margin: 0;
        }

        form {
            display: flex;
            justify-content: center;
            margin: 10px 0;
        }

        input[type="submit"] {
            background-color: #3498db;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 30%;
            box-sizing: border-box; /* Ensure padding and border don't add to the width */
            margin-bottom: 10px; /* Add some space between buttons */
        }

        .manage-btn {
            background-color: #27ae60;
        }

        /* Optional: Add hover effect for buttons */
        input[type="submit"]:hover, .manage-btn:hover {
            background-color: #2980b9;
        }

    </style>
</head>
<body>
    
    <header>
        <h1>Admin Menu</h1>
        <form id="logoutBtn" action="../logout.php" method="post">
            <input type="submit" value="Logout">
        </form>
    </header> 
        <form action="/server/announcements.php" method="post">
            <input type="submit" class="manage-btn" value="Announcements">
        </form>
        <form action="manage_store.php" method="post">
            <input type="submit" class="manage-btn" value="Manage Store">
        </form>
        <form action="new_rescuer.php" method="post">
            <input type="submit" value="New rescuer">
        </form>
        <form action="../map.php" method="post">
            <input type="submit" value="Map">
        </form>
        <form action="statistics.php" method="post">
            <input type="submit" value="Statistics">
        </form>
        <form action="addJsonStore.php" method="post">
            <input type="submit" value="add Json Store">
        </form>
</body>
</html>
