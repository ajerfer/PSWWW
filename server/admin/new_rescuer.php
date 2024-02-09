<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rescuer register</title>
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
            padding: 20px;
        }

        h2 {
            display: flex;
            margin: auto;
        }

        form {
            display: flex;
            flex-direction: column;
            max-width: 300px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        label {
            margin-bottom: 8px;
        }

        input {
            padding: 8px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #333;
            color: #fff;
            cursor: pointer;
        }
    </style>
    <link rel="stylesheet" href="../../lib/leaflet/leaflet.css" />
    <style>
        #map {
            width: 300%;
            height: 600px;
            margin-left: -300px;
        }
        #coordinates-label {
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <h2>Rescuer register</h2>

    <form action="register_rescuer.php" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        <br><br>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <br><br>
        <label for="name">Name:</label>
        <input type="text" name="name" required>
        <br><br>
        <label for="surname">Surname:</label>
        <input type="text" name="surname" required>
        <br><br>
        <label for="address">Address:</label>
        <input type="hidden" name="lat" id="lat" required>
        <input type="hidden" name="lng" id="lng" required>
        <div id="map"></div>
        <input type="submit" value="Register">
    </form>

</body>
</html>

<script src="../../lib/leaflet/leaflet.js"></script>
<script src="../../public/js/map_register.js"></script>
