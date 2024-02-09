<?php

require_once 'mongodbconnect.php';

// Check if it's an Ajax request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
    try {
        // Extract update parameters from the POST data
        $userId = $_POST['userId'];
        $id = $_POST['id'];
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
        switch ($state) {
            case "0";
                $update = [
                    '$set' => [
                        $type.'.$.dateAccepted' => null,
                        $type.'.$.state' => $state,
                        $type.'.$.rescuerId' => null
                    ]
                ];
                break;
            case "1";
                $update = [
                    '$set' => [
                        $type.'.$.dateAccepted' => new MongoDB\BSON\UTCDateTime((new DateTime())->getTimestamp()*1000+ (120*60) * 1000),
                        $type.'.$.state' => $state,
                        $type.'.$.rescuerId' => $rescuerId
                    ]
                ];
                break;
            case "2";
                $update = [
                    '$set' => [
                        $type.'.$.dateCompleted' => new MongoDB\BSON\UTCDateTime((new DateTime())->getTimestamp()*1000+ (120*60) * 1000),
                        $type.'.$.state' => $state,
                        $type.'.$.rescuerId' => $rescuerId
                    ]
                ];
                break;
        }


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