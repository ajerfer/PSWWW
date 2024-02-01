<?php

require_once 'mongodbconnect.php';

// Check if it's an Ajax request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
    try {
        // Extract update parameters from the POST data
        $idUser = $_POST['idUser'];
        $id = $_POST['id'];
        $date = $_POST['dateAccepted'];
        $state = $_POST['state'];
        $rescuerId = $_POST['rescuerId'];

        // Specify the criteria to identify documents to update
        $filter = ['idUser' => $idUser, 'offers.id' => $id];

        // Specify the field to update and its new value
        $update = [
            '$set' => [
                'offers.$.dateAccepted' => $date,
                'offers.$.state' => $state,
                'offers.$.rescuerId' => $rescuerId
            ]
        ];


        // Update a single document
        $result = $offersC->updateOne($filter, $update);

        // Check the result
        if ($result->getModifiedCount() > 0) {
            echo "Offers collection updated successfully!";
        } else {
            echo "No documents matched the criteria.";
        }
    } catch (MongoDB\Driver\Exception\Exception $e) {
        echo "Error updating Offers collection: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}

?>