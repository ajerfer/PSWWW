<?php
include_once "mongodbconnect.php";

/**
 * Function to create a new announcement.
 *
 * @param array $productsSelection - Array of selected products for the announcement.
 */
function createAnnouncement($productsSelection) {
    global $announcementsC;

    // Create a new announcement document
    $newAnnouncement = [
        "id" => uniqid(),
        "products" => $productsSelection
    ];

    try {
        // Get the first document from the collection
        $firstDocument = $announcementsC->findOne([]);

        // Check if there is at least one document in the collection
        if ($firstDocument) {
            // Add the new announcement to the 'announcements' array
            $firstDocument['announcements'][] = $newAnnouncement;

            // Update the first document with the new array of announcements
            $updateResult = $announcementsC->updateOne(
                [],
                ['$set' => ['announcements' => $firstDocument['announcements']]]
            );

            // Check if the update was successful
            if ($updateResult->getModifiedCount() > 0) {
                exit();
            } else {
                echo "Error creating announcement.";
            }
        } else {
            echo "Error: No documents found in the collection.";
        }
    } catch (MongoDB\Driver\Exception\Exception $e) {
        echo "Error creating announcement: " . $e->getMessage();
    }
}

// Check if the request method is POST and 'action' is set in POST data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    // Process based on the action
    switch ($action) {
        case 'createAnnouncement':
            if (isset($_POST['payload'])) {
                $selectedProducts = $_POST['payload'];
                createAnnouncement($selectedProducts);
            } else {
                echo "Error: Missing parameters for createAnnouncement.";
            }
            break;

        case 'deleteAnnouncement':
            if (isset($_POST['payload'])) {
                $idAnnouncement = $_POST['payload'];
                createAnnouncement($selectedProducts);
            } else {
                echo "Error: Missing parameters for deleteAnnouncement.";
            }
            break;

        default:
            echo "Error: Unknown action.";
            break;
    }
}
?>
