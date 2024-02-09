<?php
include_once "mongodbconnect.php";

//----------------------------FUNCTIONS----------------------------//

/**
 * Function to create a new announcement.
 *
 * @param array $productsSelection - Array of selected products for the announcement.
 */
function createAnnouncement($productsId, $products) {
    global $announcementsC;

    // Create a new announcement document
    $newAnnouncement = [
        "id" => uniqid(),
        "productsId" => $productsId,
        "products" => $products
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
        $cursor = $offersC->find([]);
        if ($cursor) {
            foreach($cursor as $document) { 
                foreach($document['offers'] as $offer) {
                    if ($offer['announcementId']==$announcementId && $offer['state']!=2) {
                        deleteOffer($document['userId'], $offer['id']);
                    }
                }
            }
        }

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
            $productsId = $announcement['productsId'];
            $products = $announcement['products'];
            break;
        }

    $arrayQuantities = [];  // $quantities is an object
    // Delete the empty products
    for ($i=count($products)-1; $i > -1; $i--)
        if ($quantities[$i] == 0){
            unset($productsId[$i]);
            unset($products[$i]);

        }
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
        "productsId" => $productsId,
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
        $userDoc = $offersC->findOne(['userId' => $userId]);

        // Check if there is at least one document in the collection
        if ($userDoc) {
           
            // Delete the offer 
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
        echo "Error deleting iffer: " . $e->getMessage();
    }
}

function createRequest($userId,$selectedProductId,$selectedProduct,$nPersons) {
    global $requestsC;

    // Create a new request
    $newRequest = [
        "id" => uniqid(),
        "state" => 0, 
        "rescuerId" => null,
        "dateCreated" => new MongoDB\BSON\UTCDateTime((new DateTime())->getTimestamp()*1000+ (120*60) * 1000), // Athens hour
        "dateAccepted" => null,
        "dateCompleted" => null,
        "productId" => $selectedProductId,
        "product" => $selectedProduct,
        "nPersons"=> $nPersons
    ];

    try {
        // Search for the user's offers document
        $userDoc = $requestsC->findOne(["userId" => $userId]);

        if ($userDoc) {
            
            // Update the user document 
            $updateResult = $requestsC->updateOne(
                ['_id' => $userDoc['_id']],
                ['$push' => ['requests' => $newRequest]]
            );

            // Check if the update was successful
            if ($updateResult->getModifiedCount() > 0) {
                exit();
            } else {
                echo "Error inserting request.";
            }
        } else {// If not exists, made new one
            $newDocument = [
                'code' => 1,
                'message' => "Retrieved successfully",
                'userId' => $userId,
                'requests' => [$newRequest] 
            ];

            $updateResult = $requestsC->insertOne($newDocument);

            // Check if the update was successful
            if ($updateResult) {
                exit();
            } else {
                echo "Error inserting document.";
            }
        }
    } catch (MongoDB\Driver\Exception\Exception $e) {
        echo "Error creating resquest: " . $e->getMessage();
    }
}

function deleteRequest($userId, $requestId) {
    global $requestsC;

    try {
        // Find the user's offers doc
        $userDoc = $requestsC->findOne(['userId' => $userId]);

        // Check if there is at least one document in the collection
        if ($userDoc) {
           
            // Delete the announcement wich
            $updateResult = $requestsC->updateOne(
                ['_id' => $userDoc['_id']],
                ['$pull' => ['requests' => ['id' => $requestId]]]
            );
            
            // Check if the update was successful
            if ($updateResult->getModifiedCount() > 0) {
                echo "Request deleted successfully.";
            } else {
                echo "Error deleting request.";
            }
        } else {
            echo "Error: No documents found in the collection.";
        }
    } catch (MongoDB\Driver\Exception\Exception $e) {
        echo "Error deleting request: " . $e->getMessage();
    }
}

function loadItem($userId, $itemId, $newQuantity) {
    global $productsC;
    global $vehiclesC;

    if (!is_int($newQuantity))  $newQuantity = intval($newQuantity);

    try {
        // Find the vehicle's  & warehouse docs
        $vehicleDoc = $vehiclesC->findOne(['userId' => $userId]);
        $warehouseDoc = $productsC->findOne([]);

        // Check documents
        if ($vehicleDoc && $warehouseDoc) {
           
            $item = "";
            foreach ($warehouseDoc['items'] as $i)
                if ($i['id'] == $itemId) {
                    $item = $i;
                    break;
                }

            if ($item['quantity'] < $newQuantity) {
                // Si hay un error, devolver una respuesta de error
                echo "Error: quantity modified.";
                return;
            }

            // Decrement the quantity
            $updateResult = $productsC->updateOne(
                ['_id' => $warehouseDoc['_id'], 'items.id' => $itemId],
                ['$inc' => ['items.$.quantity' => -$newQuantity]]
            );
            
            // Check if the update was successful
            if ($updateResult->getModifiedCount() > 0) {

                $result = $vehiclesC->findOne([
                    '_id' => $vehicleDoc['_id'],
                    'load.id' => $itemId
                ]);
                //print_r($result);

                if ($result) {
                    $updateResult = $vehiclesC->updateOne(
                        ['userId' => $userId, 'load.id' => $itemId],
                        ['$inc' => ['load.$.quantity' => $newQuantity]]
                    );
                } else {
                    $item['quantity'] = $newQuantity;
                    
                    $updateResult = $vehiclesC->updateOne(
                        ['_id' => $vehicleDoc['_id']],
                        ['$push' => ['load' => $item]]);
                }
                
                if ($updateResult->getModifiedCount() <= 0) {
                    echo "Error adding items.";
                } else
                    echo "Item loaded correctly.";


            } else {
                echo "Error changing quantity.";
            }

        } else {
            echo "Error: No documents found in the collection.";
        }
    } catch (MongoDB\Driver\Exception\Exception $e) {
        echo "Error loading item: " . $e->getMessage();
    }
}

function unloadVehicle($userId) {
    global $productsC;
    global $vehiclesC;


    try {
        // Find the vehicle's  & warehouse docs
        $vehicleDoc = $vehiclesC->findOne(['userId' => $userId]);
        $warehouseDoc = $productsC->findOne([]);

        // Check documents
        if ($vehicleDoc && $warehouseDoc) {
           
            $item = "";
            foreach ($vehicleDoc['load'] as $i) {
                $updateResult = $productsC->updateOne(
                    ['_id' => $warehouseDoc['_id'], 'items.id' => $i['id']],
                    ['$inc' => ['items.$.quantity' => intval($i['quantity'])]]
                );
                if ($updateResult->getModifiedCount() <=0) {
                    echo "Error adding quantity. ";
                }
            }

            // Change the vehicle doc.
            $updateResult = $vehiclesC->updateOne(
                ['userId' => $userId],
                ['$set' => ['load' => []]]
            );
            
            // Check if the update was successful
            if ($updateResult->getModifiedCount() <= 0) {
                echo "Error changing load.";
            }

        } else {
            echo "Error: No documents found in the collection.";
        }
    } catch (MongoDB\Driver\Exception\Exception $e) {
        echo "Error unloading vehicle: " . $e->getMessage();
    }
}


function completeRequest($userId, $requestId, $rescuerId) {
    global $requestsC;
    global $vehiclesC;
    
    try {
        // Find doc
        $requestDoc = $requestsC->findOne(['userId' => $userId]);

        if ($requestDoc) {
            
            $request = "";
            foreach ($requestDoc['requests'] as $r)
                if ($r['id'] == $requestId) {
                    $request = $r;
                    break;
                }

            $vehicleDoc = $vehiclesC->findOne(['userId' => $rescuerId]);

            if ($vehicleDoc) {

            }
            
            
            
            // Delete the offer 
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
            echo "Error: No documents found in the requests collection.";
        }
    } catch (MongoDB\Driver\Exception\Exception $e) {
        echo "Error deleting iffer: " . $e->getMessage();
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
                $productsId = $_POST['payload']['productsId'];
                $products = $_POST['payload']['products'];
                
                createAnnouncement($productsId, $products);
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
                echo "Error: Missing parameters for deleteOffer.";
            }
            break;
            
        case 'createRequest':
            if (isset($_POST['payload'])) {
                $userId = $_POST['payload']['userId'];
                $selectedProductId = $_POST['payload']['selectedProductId'];
                $selectedProduct = $_POST['payload']['selectedProduct'];
                $nPersons = $_POST['payload']['nPersons'];
                
                createRequest($userId,$selectedProductId,$selectedProduct,$nPersons);
            } else {
                echo "Error: Missing parameters for createRequest.";
            }
            break;

        case 'deleteRequest':
            if (isset($_POST['payload'])) {
                $userId = $_POST['payload']['userId'];
                $requestId = $_POST['payload']['requestId'];
                
                deleteRequest($userId,$requestId);
            } else {
                echo "Error: Missing parameters for deleteRequest.";
            }
            break;

        case 'loadItem':
            if (isset($_POST['payload'])) {
                $userId = $_POST['payload']['userId'];
                $itemId = $_POST['payload']['itemId'];
                $newQuantity= $_POST['payload']['newQuantity'];
                
                loadItem($userId,$itemId, $newQuantity);
            } else {
                echo "Error: Missing parameters for loadItem.";
            }
            break;

            case 'unloadVehicle':
                if (isset($_POST['payload'])) {
                    $userId = $_POST['payload']['userId'];
                    
                    unloadVehicle($userId);
                } else {
                    echo "Error: Missing parameters for unloadVehicle.";
                }
                break;

            case 'completeRequest':
                if (isset($_POST['payload'])) {
                    $userId = $_POST['payload']['userId'];
                    $requestId = $_POST['payload']['requestId'];
                    $rescuerId = $_POST['payload']['rescuerId'];
                    
                    completeRequest($userId, $requestId, $rescuerId);
                } else {
                    echo "Error: Missing parameters for completeRequest.";
                }
                break;

            case 'completeOffer':
                if (isset($_POST['payload'])) {
                    $userId = $_POST['payload']['userId'];
                    $requestId = $_POST['payload']['requestId'];
                    $rescuerId = $_POST['payload']['rescuerId'];
                    
                    completeOffer($userId, $requestId, $rescuerId);
                } else {
                    echo "Error: Missing parameters for completeOffer.";
                }
                break;
    
        default:
            echo "Error: Unknown action.";
            break;
    }
}
?>
