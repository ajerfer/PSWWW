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

$categoryFilter = null;

$categoryFilter = isset($_POST['category']) ? $_POST['category'] : [];

if (empty($categoryFilter)) {
    // Si no hay categorías seleccionadas, obtén todos los productos
    $products = getAllProducts();
} else {
   // Si hay categorías seleccionadas, filtra los productos por categoría 6
   foreach ($categoryFilter as $category) {
        $aggregation = [
            [
                '$unwind' => '$items'
            ],
            [
                '$match' => [
                    'items.category' => $category
                ]
            ],
            [
                '$group' => [
                    '_id' => '$_id',
                    'items' => [
                        '$push' => '$items'
                    ]
                ]
            ]
        ];
        $results[] = $productsCollection->aggregate($aggregation)->toArray();
    }

    $products = array_merge(...$results);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../public/styles.css">
    <title>Products</title>
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
    <h1>List of Products</h1>
    <form method="get" action="filter_categories.php">
        <button type="submit">Filter</button>
    </form>
    <?php foreach ($products as $product): ?>
        <div>
            <ul>
                <?php foreach ($product['items'] as $item): ?>
                    <li><?= $item['name']; ?> <br>
                        <ul>
                        <li>Quantity: <?= $item['quantity']; ?></li>
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
