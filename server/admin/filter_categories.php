<?php
session_start();
include_once '../mongodbconnect.php';

$productsCollection = $dataBase->Products;
$uniqueCategories = $productsCollection->distinct('categories', [], ['id', 'category_name']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../public/styles.css">
    <title>Filter Categories</title>
</head>
<body>
    <h1>Filter Categories</h1>
    
    <form method="post" action="manage_store.php">
        <table border="1">
            <thead>
                <tr>
                    <th>Category ID</th>
                    <th>Category Name</th>
                    <th>Select</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($uniqueCategories as $category): ?>
                    <tr>
                        <td><?= $category['id'] ?></td>
                        <td><?= $category['category_name'] ?></td>
                        <td>
                            <input type="checkbox" name="category[]" value="<?= $category['id'] ?>">
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <button type="submit">Filter</button>
    </form>

    <br>
    <a href="manage_store.php">Back to Manage Store</a>
</body>
</html>
