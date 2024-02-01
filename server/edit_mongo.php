<?php
include_once "mongodbconnect.php";

//----------------------------FUNCTIONS----------------------------//

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
            // Update the first document adding the new announcement
            $updateResult = $announcementsC->updateOne(
                ['_id' => $firstDocument["_id"]],
                ['$push' => ['announcements' => $newAnnouncement]]
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

/**
 * Function to delete an announcement based on its ID.
 *
 * @param string $idAnnouncement - ID of the announcement to be deleted.
 */
function deleteAnnouncement($idAnnouncement) {
    global $announcementsC;

    try {
        // Find the first document in the collection
        $firstDocument = $announcementsC->findOne([]);
        $aux = $firstDocument['_id'];
        echo "Id first document $aux \n";
        echo "Id announcement $idAnnouncement \n";

        // Check if there is at least one document in the collection
        if ($firstDocument) {
           
            // Delete the announcement wich
            $updateResult = $announcementsC->updateOne(
                ['_id' => $firstDocument['_id']],
                ['$pull' => ['announcements' => ['id' => $idAnnouncement]]]
            );
            
            // Check if the update was successful
            if ($updateResult->getModifiedCount() > 0) {
                echo "Announcement deleted successfully.";
            } else {
                echo "Error deleting announcement.";
            }
        } else {
            echo "Error: No documents found in the collection.";
        }
    } catch (MongoDB\Driver\Exception\Exception $e) {
        echo "Error deleting announcement: " . $e->getMessage();
    }
}


//------------------------CHOOSING FUNCTION------------------------//


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
                deleteAnnouncement($idAnnouncement);
            } else {
                echo "Error: Missing parameters for deleteAnnouncement.";
            }
            break;

        case 'createOffert':
            if (isset($_POST['payload'])) {
                $selectedProducts = $_POST['payload'];
                $idUser = $_POST['payload']['idUser'];
                $idAnnouncement = $_POST['payload']['idAnnouncement'];
                $valores = $_POST['payload']['valores'];
                echo $valores;
                createOffer($selectedProducts);
            } else {
                echo "Error: Missing parameters for createAnnouncement.";
            }
            break;

        default:
            echo "Error: Unknown action.";
            break;
    }
}
?>
