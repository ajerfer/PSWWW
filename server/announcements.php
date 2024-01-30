<?php

include_once "mongodbconnect.php";

// Obtener la colección de anuncios y de productos de MongoDB
try {
    $announcementsC = $dataBase->Announcements;
    $productsC = $dataBase->Products;
    
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Error selecting the collection: " . $e->getMessage();
}

// Obtener documentos de la colección
$cursorP = $productsC->find();
$cursorA = $announcementsC->find();

// Array para almacenar los nombres de los productos
$productsNames = [];

// Recorrer los productos y extraer los nombres
foreach ($cursorP as $document) {
    foreach ($document['items'] as $item) {
            $productsNames[] = $item['name'];
    }
}
?>

<script>
    function openPopupBox(id) {
        const modal = document.getElementById('modal' + id);
        modal.style.display = 'block';
    }

    function closePopupBox(id) {
        const modal = document.getElementById('modal' + id);
        modal.style.display = 'none';
    }

    function getQuantities(inputModal, n) {
        const quantities = [];
        for (let i = 0; i < n; i++) {
            const input = document.querySelector(`[name="${inputModal}${i}_modal"]`);
            quantities.push(parseInt(input.value));
        }
        return quantities;
    }

    function createOffer(idAnuncio, valores) {
        // AQUI FALTA EL CÓDIGO PARA CREAR LA OFERTA Y REDIRIGIR AL PHP DE OFERTAS
        console.log(`Creando oferta para el anuncio ${idAnuncio} con valores:`, valores);
    }

    function addProduct() {
        // Si selectedProducts no es un array, inicialízalo como un array vacío
        if (!Array.isArray(selectedProducts)) {
            selectedProducts = [];
        }
        
        
        // Obtener el valor seleccionado de la lista desplegable
        const selectedProduct = document.getElementById('productDropdown').value;

        // Añadir el producto al array
        selectedProducts.push(selectedProduct);

        // Mostrar el array de productos en el cuadro emergente
        showProducts();
    }

    function showProducts() {
        const productsContainer = document.getElementById('selectedProducts');
        productsContainer.innerHTML = '<strong>Selected Products:</strong>';

        selectedProducts.forEach(product => {
            const productItem = document.createElement('p');
            productItem.textContent = product;
            productsContainer.appendChild(productItem);
        });
    }


    function cretateAnnouncement(productsSelection, n) {
        
    }
</script>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../public/styles.css">
    <title>Announcements List</title>
    <style>
        body {
            margin: 30px;
        }
        .announcement-box {
            border: 2px solid #ccc;
            padding-top: 10px;
            padding-bottom: 30px;
            padding-left: 30px;
            padding-right: 30px;
            margin: 0px;
            margin-bottom: 10px;
            margin-top: 10px;
            text-align: left;
        }
        .btn-container {
            margin-top: auto;
            margin-top: 20px;
        }
        .input-container {
            margin-bottom: 10px;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.7);
        }

        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
        }
    </style>
</head>
<body>

<h1>Announcements List</h1>

<!-- New Announcement Button -->
<button onclick="openPopupBox('newAnnouncement')">Create Announcement</button>

<?php foreach ($cursorA as $document): ?>
    <?php foreach ($document['announcements'] as $announcement): ?>
        <div class="announcement-box">
            <h3>Announcement</h3>
                <ul>
                    <?php foreach ($announcement['products'] as $string): ?>
                        <li><?php echo htmlspecialchars($string); ?></li>
                        
                    <?php endforeach; ?>
                </ul>
            <!-- Button - Only for citizen -->
            <!-- if ($_SESSION['role'] === 'citizen'): -->
                <button onclick="openPopupBox(<?= $announcement['id'] ?>)">Make offer</button>
            <!-- endif; -->
        </div>
        <!-- Popup box -->
        <div id="modal<?= $announcement['id'] ?>" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closerPopup(<?= $announcement['id'] ?>)">&times;</span>
                <?php foreach ($announcement['products'] as $index => $product): ?>
                    <p><?= $product ?></p>
                    <input type="number" name="input<?= $index ?>_modal" class="input_modal" min="0" value="0">
                <?php endforeach; ?>
                <div class="btn-container">
                    <button onclick="closePopupBox(<?= $announcement['id'] ?>)">Close</button>
                    <button onclick="createOffer(<?= $announcement['id'] ?>, getQuantities('input_modal', <?= count($announcement['products']) ?>))">Create offer</button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endforeach; ?>

<!-- New Announcement Popup Box -->
<div id="modalnewAnnouncement" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closePopupBox('newAnnouncement')">&times;</span>
        <h2>New Announcement</h2>
        <div class="input-container">
            <label for="productDropdown">Select a Product:</label>
            <select id="productDropdown">
                <?php foreach ($productsNames as $productName): ?>
                    <option value="<?= $productName ?>"><?= $productName ?></option>
                <?php endforeach; ?>
            </select>
            <button onclick="addProduct()">Add Product</button>
        </div>
        <div id="selectedProducts"></div>
        <div class="btn-container">
            <button onclick="closePopupBox('newAnnouncement')">Close</button>
            <button onclick="createAnnouncement('newAnnouncement')">Create</button>
        </div>
    </div>
</div>

</body>
</html>