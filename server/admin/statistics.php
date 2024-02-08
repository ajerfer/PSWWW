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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/luxon"></script>
    <script src="statistic.js"></script>
    <title>Statistics</title>
</head>
<body>
    <canvas id="myChart"></canvas>
    
    <div id="controls">
        <label for="startDate">Start Date:</label>
        <input type="month" id="startDate" name="startDate" value="2022-01">
        
        <label for="endDate">End Date:</label>
        <input type="month" id="endDate" name="endDate" value="2022-12" >
        
        <button id="updateChart">Update Chart</button>
    </div>

    
</body>
</html>
