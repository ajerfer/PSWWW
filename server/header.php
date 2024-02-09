<?php
if (isset($_SESSION['role'])) {
    $url = '';
    switch ($_SESSION['role']) {
        case 'admin':
            $url = '/server/admin/admin_page.php';
            break;
        case 'rescuer':
            $url = '/server/rescuer/rescuer_page.php';
            break;
        case 'citizen':
            $url = '/server/citizen/citizen_page.php';
            break;
    }
} 
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../public/styles.css">
    <title>Web Programming</title>
    <style>
        .navbar {
            display: flex;
            justify-content: space-between; 
            align-items: center; 
        }

        .navbar button {
            margin: 5px; 
        }
    </style>
</head>
<body>

<div class="navbar">
    <button style="margin-left: 10px;" onclick="window.location.href = '<?php echo $url; ?>'">Back Menu</button>
    <button onclick="window.location.href='/server/logout.php'" style="margin-right: 10px;">Log Out</button>
</div>
