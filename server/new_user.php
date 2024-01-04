<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" type="text/css" href="public/styles.css">
</head>
<body>

    <h2>Registro de Usuario</h2>

    <form action="procesar_registro.php" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" required>

        <label for="password">Password:</label>
        <input type="password" name="password" required>

        <label for="name">Name:</label>
        <input type="text" name="nombre" required>

        <label for="telefono">Phone:</label>
        <input type="tel" name="phone" required>

        <label for="address">Address:</label>
        <input type="text" name="address" required>

        <input type="submit" value="Register">
    </form>

</body>
</html>
