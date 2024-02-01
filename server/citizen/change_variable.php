<?php

session_start();

// // Verify the user 
// if (!isset($_SESSION['userId']) || $_SESSION['role'] !== 'admin' || $_SESSION['role'] !== 'citizen') {
//     header("Location: ../index.php"); // Redirigir a la página de inicio de sesión
//     exit();
// } 

include_once "../mongodbconnect.php";

// Save the userId and is document associated
$userId = 3/*$_SESSION['userId']*/;
$documentO = $offersC->findOne(['idUser' => $userId]);

// Divide the offers
$offers = ['active' => [], 'completed' => []];
if ($documentO) {
    foreach ($documentO['offers'] as $offer) {
        $category = ($offer['state'] == 2) ? 'completed' : 'active';
        
        $offers[$category][] = $offer;
    }
}


// Variable that specifies the section and its first initialization
$section = "active"; 
    
// // Change the section
// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['section'])) 
//     $section = $_POST['section'];


// echo $section;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <script src="../../lib/jquery-3.7.1.js" ></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selector de Secciones</title>

    <style>
        .seccionButton {
            padding: 10px;
            margin-right: 10px;
            cursor: pointer;
        }

        .seccionButton:hover {
            background-color: #333;
            color: white;
        }

        .offer-box {
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

    </style>
</head>
<body>

    <h1>My Offers</h1>

    <!-- Section Buttons -->
    <button class="seccionButton" onclick="showSection('active')" style="background-color:#333; color:white;">ACTIVE OFFERS</button>
    <button class="seccionButton" onclick="showSection('completed')">COMPLETED OFFERS</button>
    
    <div id=offersContent>
        <?php foreach ($offers[$section] as $offer): ?>    
            <!-- Offer Box -->
            <div class="offer-box"> 
                <h3>Offer <?= $offer['dateCreated'] ?></h3>
                <ul>
                    <!-- Show the products offered -->
                    <li>Products offered: </li>
                    <ul>
                    <?php for ($i = 0; $i < count($offer['products']); $i++): ?>
                        <?php if ($offer['nProducts'][$i] != 0): ?>
                            <li><?= $offer['products'][$i]; ?> (<?= $offer['nProducts'][$i]; ?>)</li>
                        <?php endif; ?>
                    <?php endfor; ?>
                    </ul>       
                    <!-- Show the state and the corresponding dates -->
                    <?php if ($offer['state'] == 0): ?>
                        <li>State: ACTIVE</li> 
                    <?php elseif ($offer['state'] == 1): ?>
                        <li>State: ACCEPTED</li>
                        <li>Date Accepted: <?= $offer['dateAccepted'] ?> </li>
                    <?php else: ?>
                        <li>State: COMPLETED</li>
                        <li>Date Accepted: <?= $offer['dateAccepted'] ?> </li>
                        <li>Date Completed: <?= $offer['dateCompleted'] ?> </li>
                    <?php endif; ?>
                </ul>
                    <button onclick="callDeleteAnnouncement('<?= $userId ?>', '<?= $offer['id'] ?>')">Delete</button>
            </div>
        <?php endforeach; ?>
    </div>
    <script>
        // Var to save the actual section
        var actualSection = "active";

        changeButtonStyle(actualSection);

        function showSection(newSection) {
            
            // Check the user's response
            if (actualSection != newSection) {

                actualSection= newSection;

                // Realiza una llamada AJAX para cambiar la sección
                $.post("", { newSection: newSection })
                    .done(function () {
                        // Éxito: Recarga el contenido del div con id "offersContent"
                        $("#offersContent").load(location.href + " #offersContent", function () {    
                        });
                        
                        changeButtonStyle(newSection);
                    })
                    .fail(function (xhr, status, error) {
                        // Error: Maneja los errores aquí
                        console.error("Error al cambiar la sección:", status, error);
                    });
                
                
                // changeButtonStyle(newSection); 
            }
        }

        function changeButtonStyle(section) {
            // Cambiar el estilo de los botones según la sección seleccionada
            var botones = document.querySelectorAll(".seccionButton");
                    
            botones.forEach(function (boton) {
                if (boton.getAttribute("onclick").includes(actualSection)) {
                    boton.style.backgroundColor = "#333";
                    boton.style.color = "white";
                } else {
                    boton.style.backgroundColor = "";
                    boton.style.color = "";
                }
            });
        }
        
    </script>
</body>
</html>
