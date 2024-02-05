<?php

require_once 'mongodbconnect.php';

// Check if it's an Ajax request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
    try {
        // Extract update parameters from the POST data
        $userId = $_POST['userId'];
        $id = $_POST['id'];
        $date = $_POST['dateAccepted'];
        $state = $_POST['state'];
        $rescuerId = $_POST['rescuerId'];

        // Specify the criteria to identify documents to update
        if ($_POST['type'] == "offer") {
            $type = 'offers';
        } else if ($_POST['type'] == "request") {
            $type = 'requests';
        }

        $filter = ['userId' => $userId, $type.'.id' => $id];

        // Specify the field to update and its new value
        $update = [
            '$set' => [
                $type.'.$.dateAccepted' => $date,
                $type.'.$.state' => $state,
                $type.'.$.rescuerId' => $rescuerId
            ]
        ];


        // Update a single document
        if ($_POST['type'] == "offer") {
            $result = $offersC->updateOne($filter, $update);
        } else if ($_POST['type'] == "request") {
            $result = $requestsC->updateOne($filter, $update);
        }

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