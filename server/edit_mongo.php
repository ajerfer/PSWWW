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
 * @param string $announcementId - ID of the announcement to be deleted.
 */
function deleteAnnouncement($announcementId) {
    global $announcementsC;
    global $offersC;

    
    
    try {
        
        // Delete the offers associated not completed
        $cursor = $offersC->find([one]);    
        foreach($cursor as $document) 
            foreach($document['offers'] as $offer)
                if ($offer['announcementId']==$announcementId && $offer['state']!=2)
                    deleteOffer($document['userId'],$offer['id']);


        // Find the first document in the collection
        $firstDocument = $announcementsC->findOne([]);

        // Check if there is at least one document in the collection
        if ($firstDocument) {
           
            // Delete the announcement wich
            $updateResult = $announcementsC->updateOne(
                ['_id' => $firstDocument['_id']],
                ['$pull' => ['announcements' => ['id' => $announcementId]]]
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


function createOffer($userId,$announcementId,$quantities) {
    global $announcementsC;
    global $offersC;

    // Find the announcement 
    $firstDocument = $announcementsC->findOne();
    
    foreach ($firstDocument['announcements'] as $announcement)
        if ($announcement['id'] == $announcementId){
            $products = $announcement['products'];
            break;
        }

    $arrayQuantities = [];  // $quantities is an object
    // Delete the empty products
    for ($i=count($products)-1; $i > -1; $i--)
        if ($quantities[$i] == 0) 
            unset($products[$i]);
        else
            array_unshift($arrayQuantities, $quantities[$i]);

    // Create a new offer
    $newOffer = [
        "id" => uniqid(),
        "announcementId" => $announcementId,
        "state" => 0, 
        "rescuerId" => null,
        "dateCreated" => new MongoDB\BSON\UTCDateTime((new DateTime())->getTimestamp()*1000+ (120*60) * 1000), // Athens hour
        "dateAccepted" => null,
        "dateCompleted" => null,
        "products" => $products,
        "nProducts"=> $arrayQuantities
    ];

    try {
        // Search for the user's offers document
        $userDoc = $offersC->findOne(["userId" => $userId]);

        
        if ($userDoc) {
            
            // Update the user document 
            $updateResult = $offersC->updateOne(
                ['_id' => $userDoc['_id']],
                ['$push' => ['offers' => $newOffer]]
            );

            // Check if the update was successful
            if ($updateResult->getModifiedCount() > 0) {
                exit();
            } else {
                echo "Error inserting offer.";
            }
        } else {// If not exists, made new one
            $newDocument = [
                'code' => 1,
                'message' => "Retrieved successfully",
                'userId' => $userId,
                'offers' => [$newOffer] 
            ];

            $updateResult = $offersC->insertOne($newDocument);

            // Check if the update was successful
            if ($updateResult) {
                exit();
            } else {
                echo "Error inserting document.";
            }
        }
    } catch (MongoDB\Driver\Exception\Exception $e) {
        echo "Error creating offer: " . $e->getMessage();
    }
}

function deleteOffer($userId, $offerId) {
    global $offersC;

    try {
        // Find the user's offers doc
        $userDoc = $offersC->findOne([]);

        // Check if there is at least one document in the collection
        if ($userDoc) {
           
            // Delete the announcement wich
            $updateResult = $offersC->updateOne(
                ['_id' => $userDoc['_id']],
                ['$pull' => ['offers' => ['id' => $offerId]]]
            );
            
            // Check if the update was successful
            if ($updateResult->getModifiedCount() > 0) {
                echo "Offer deleted successfully.";
            } else {
                echo "Error deleting offer.";
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
                $announcementId = $_POST['payload'];
                
                deleteAnnouncement($announcementId);
            } else {
                echo "Error: Missing parameters for deleteAnnouncement.";
            }
            break;

        case 'createOffer':
            if (isset($_POST['payload'])) {
                $userId = $_POST['payload']['userId'];
                $announcementId = $_POST['payload']['announcementId'];
                $quantities = $_POST['payload']['quantities'];
                
                createOffer($userId,$announcementId,$quantities);
            } else {
                echo "Error: Missing parameters for createOffer.";
            }
            break;

        case 'deleteOffer':
            if (isset($_POST['payload'])) {
                $userId = $_POST['payload']['userId'];
                $offerId = $_POST['payload']['offerId'];
                
                deleteOffer($userId,$offerId);
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
