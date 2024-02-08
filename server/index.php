<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Página Web</title>
    <style>
		body, h1, h2, form {
			margin: 0;
			padding: 0;
		}

		body {
			background-color: #f2f2f2;
			color: #333;
			font-family: Arial, sans-serif;
		}

		header {
			background-color: #333;
			color: #fff;
			text-align: center;
			padding: 20px;
		}

		.login-container {
			max-width: 400px;
			margin: 20px auto;
			background-color: #fff;
			padding: 20px;
			border-radius: 8px;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
		}

		form {
			display: flex;
			flex-direction: column;
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

		button {
			position: center;
			background-color: #333;
			color: #fff;
			padding: 10px 15px;
			border: none;
			border-radius: 4px;
			cursor: pointer;
		}

		a button {
			margin-top: 10px;
			background-color: #555;
		}

	</style>
</head>
<body>
    <header>
        <h1>Bienvenido a mi Página Web</h1>
    </header>
    <div class="login-container">
	<h2>Login<br></h2>
	<form action="login.php" method="post">
		<label for="username"><br>User:</label>
		<input type="text" id="username" name="username" required>
		<br><br>
		<label for="password">Password:</label>
		<input type="password" id="password" name="password" required>
		<br><br>
		<button type="submit">Login</button>
		<br><br>
	</form>
	</div>
	<a href="new_user.php">
		<button>Crear Usuario</button>
	</a>
</body>
</html>
