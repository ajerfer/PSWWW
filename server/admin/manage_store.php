<?php
session_start();
include_once '../mongodbconnect.php';


// Verificar si el usuario ha iniciado sesión y es un administrador
if (!isset($_SESSION['userId']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php"); // Redirigir a la página de inicio de sesión
    exit();
}

// Seleccionar la colección de productos
$productsCollection = $dataBase->Products;

function getAllProducts() {
    global $productsCollection;
    return $productsCollection->find();
}

function getProductsByCategory($category) {
    global $productsCollection;
    // Configurar la etapa de agregación
    $aggregation = [
        [
            '$project' => [
                'items' => [
                    '$filter' => [
                        'input' => '$items',
                        'as' => 'item',
                        'cond' => ['$eq' => ['$$item.category', $category]]
                    ]
                ]
            ]
        ]
    ];
    return $productsCollection->aggregate($aggregation)->toArray();
}
$categoryFilter = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category'])) {
    $categoryFilter = !empty($_POST['category']) ? $_POST['category'] : null;
}
if ($categoryFilter === 'all') {
    $productos = getAllProducts();
} elseif ($categoryFilter) {
    $productos = getProductsByCategory($categoryFilter);
} else {
    // Obtener todos los productos si no hay filtro
    $productos = getAllProducts();
}

$uniqueCategories = $productsCollection->distinct('categories', [],['id', 'category_name']);

$productos = $productsCollection->find();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../public/styles.css">
    <title>Productos</title>
    <style>
        body {
            margin-top: 10px; /* Espacio para el botón fijo */
        }

        .backBtn {
            position: absolute;
            top: 10px;
            left: 10px;
        }
        .logout-btn {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .addProductBtn {
            position: absolute;
            top: 10px;
            right: 70px; /* Ajusta la posición según tu diseño */
        }
    </style>
</head>
<body>
    <form id="backBtn" action="admin_page.php" method="post">
        <button type="submit">Back</button>
    </form>
    <form class="logout-btn" action="../logout.php" method="post">
        <input type="submit" value="Logout">
    </form>
    <form class="addProductBtn" action="add_product.php" method="post">
        <button type="submit">Add Product</button>
    </form>
    <h1>Lista de Productos</h1>
    <form method="post" action="manage_store.php">
        <label for="category">Filter by Category:</label>
        <select name="category[]" id="category" multiple>
            <option value="all" <?= empty($_POST['category']) ? 'selected' : '' ?>>Show All</option>
            <?php foreach ($uniqueCategories as $category): ?>
                <option value="<?= $category['id'] ?>" <?= isset($_POST['category']) && in_array($category['id'], (array)$_POST['category']) ? 'selected' : '' ?>>
                    <?= $category['category_name'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Filter</button>
    </form>
    <?php foreach ($productos as $producto): ?>
        <div>
            <ul>
                <?php foreach ($producto['items'] as $item): ?>
                    <li><?= $item['name']; ?>
                        <ul>
                            <?php foreach ($item['details'] as $detail): ?>
                                    <li><?= $detail['detail_name'] . ': ' . $detail['detail_value']; ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <br>
                        <form action="modifyProduct.php" method="post" style="display: inline;">
                            <input type="hidden" name="itemId" value="<?= $item['id']; ?>">
                            <button type="submit">Modify Product</button>
                        </form>
                        <form action="deleteProduct.php" method="post" style="display: inline;">
                            <input type="hidden" name="itemId" value="<?= $item['id']; ?>">
                            <button type="submit">Delete Product</button>
                        </form>
                    </li>
                    <br>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>
</body>
</html>
