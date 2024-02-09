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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/luxon"></script>
    <script src="statistic.js"></script>
    <title>Statistics</title>
    <style>
    #myChartContainer {
        margin: auto;
        width: 90%;
        height: 90%;
        position: center;
    }
    </style>
</head>
<body>
    <div id="myChartContainer">
        <canvas id="myChart"></canvas>
        
        <div id="controls">
            <label for="startDate">Start Date:</label>
            <input type="month" id="startDate" name="startDate" value="2022-01">
            
            <label for="endDate">End Date:</label>
            <input type="month" id="endDate" name="endDate" value="2022-12" >
            
            <button id="updateChart">Update Chart</button>
        </div>
    </div>
</body>
</html>
