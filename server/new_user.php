<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" type="text/css" href="../public/styles.css">
    <style>
        form {
            display: flex;
            flex-direction: column;
            max-width: 300px;
            margin: auto;
        }

        label {
            margin-bottom: 5px;
        }

        input {
            margin-bottom: 10px;
            padding: 5px;
        }

        input[type="submit"] {
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <h2>Registro de Usuario</h2>

    <form action="register_user.php" method="post">
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
        <label for="phone">Phone:</label>
        <input type="tel" name="phone" required>
        <br><br>
        <label for="address">Address:</label>
        <input type="text" name="address" required>
        <br><br>
        <input type="submit" value="Register">
    </form>

</body>
</html>
