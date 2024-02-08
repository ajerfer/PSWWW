<?php
session_start();

if (!isset($_SESSION['userId']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php"); 
    exit();
}
include_once '../mongodbconnect.php';
global $dataBase;

function newOffersQueryByMonth($monthYear) {
    global $dataBase;

    $offersCollection = $dataBase->Offers;

    $yearMonth = explode('-', $monthYear);
    $year = $yearMonth[0];
    $month = $yearMonth[1];

    $startOfMonth = new DateTime("$year-$month-01");
    $endOfMonth = new DateTime("$year-$month-" . $startOfMonth->format('t'));

    $startDateTime = new MongoDB\BSON\UTCDateTime($startOfMonth->getTimestamp() * 1000);
    $endDateTime = new MongoDB\BSON\UTCDateTime($endOfMonth->getTimestamp() * 1000);
    $query = [
        ['$unwind' => '$offers'],
        [
            '$match' => [
                'offers.dateCreated' => [
                    '$gte' => $startDateTime,
                    '$lte' => $endDateTime
                ]
            ]
        ],
        ['$count' => 'totalOffers']
    ];
    $newOffers = 0;
    $newOffersCursor = $offersCollection->aggregate($query);
    foreach ($newOffersCursor as $result) {
        $newOffers = $result->totalOffers;
    }
    return $newOffers;
}

function newOffersQuery($startDate, $endDate) {
    $counter = 0;
    $newOffers = [];
    $interval = [];
    $interval = getMonthRange($startDate, $endDate);
    foreach ($interval as $month) {
        $newOffers[$counter++] = newOffersQueryByMonth($month);
    }
    return $newOffers;
}

function getMonthRange($startDate, $endDate) {
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
    $months = [];

    while ($start <= $end) {
        $months[] = $start->format('Y-m');
        $start->modify('+1 month');
    }

    return $months;
}

function processedOffersQueryByMonth($monthYear) {
    global $dataBase;

    $offersCollection = $dataBase->Offers;

    $yearMonth = explode('-', $monthYear);
    $year = $yearMonth[0];
    $month = $yearMonth[1];

    $startOfMonth = new DateTime("$year-$month-01");
    $endOfMonth = new DateTime("$year-$month-" . $startOfMonth->format('t'));

    $startDateTime = new MongoDB\BSON\UTCDateTime($startOfMonth->getTimestamp() * 1000);
    $endDateTime = new MongoDB\BSON\UTCDateTime($endOfMonth->getTimestamp() * 1000);
    $query = [
        ['$unwind' => '$offers'],
        [
            '$match' => [
                'offers.dateCompleted' => [
                    '$gte' => $startDateTime,
                    '$lte' => $endDateTime
                ]
            ]
        ],
        ['$count' => 'totalOffers']
    ];
    $processedOffers = 0;
    $processedOffersCursor = $offersCollection->aggregate($query);
    foreach ($processedOffersCursor as $result) {
        $processedOffers = $result->totalOffers;
    }
    return $processedOffers;
}

function processedOffersQuery($startDate, $endDate) {
    $counter = 0;
    $offers = [];
    $interval = [];
    $interval = getMonthRange($startDate, $endDate);
    foreach ($interval as $month) {
        $offers[$counter++] = processedOffersQueryByMonth($month);
    }
    return $offers;
}

function newRequestsQueryByMonth($monthYear) {
    global $dataBase;

    $requestsCollection = $dataBase->Requests;

    $yearMonth = explode('-', $monthYear);
    $year = $yearMonth[0];
    $month = $yearMonth[1];

    $startOfMonth = new DateTime("$year-$month-01");
    $endOfMonth = new DateTime("$year-$month-" . $startOfMonth->format('t'));

    $startDateTime = new MongoDB\BSON\UTCDateTime($startOfMonth->getTimestamp() * 1000);
    $endDateTime = new MongoDB\BSON\UTCDateTime($endOfMonth->getTimestamp() * 1000);
    $query = [
        ['$unwind' => '$requests'],
        [
            '$match' => [
                'requests.dateCreated' => [
                    '$gte' => $startDateTime,
                    '$lte' => $endDateTime
                ]
            ]
        ],
        ['$count' => 'totalRequests']
    ];
    $newRequests = 0;
    $newRequestsCursor = $requestsCollection->aggregate($query);
    foreach ($newRequestsCursor as $result) {
        $newRequests = $result->totalRequests;
    }
    return $newRequests;
}

function newRequestsQuery($startDate, $endDate) {
    $counter = 0;
    $newRequests = [];
    $interval = [];
    $interval = getMonthRange($startDate, $endDate);
    foreach ($interval as $month) {
        $newRequests[$counter++] = newRequestsQueryByMonth($month);
    }
    return $newRequests;
}

function processedRequestsQueryByMonth($monthYear) {
    global $dataBase;

    $requestsCollection = $dataBase->Requests;

    $yearMonth = explode('-', $monthYear);
    $year = $yearMonth[0];
    $month = $yearMonth[1];

    $startOfMonth = new DateTime("$year-$month-01");
    $endOfMonth = new DateTime("$year-$month-" . $startOfMonth->format('t'));

    $startDateTime = new MongoDB\BSON\UTCDateTime($startOfMonth->getTimestamp() * 1000);
    $endDateTime = new MongoDB\BSON\UTCDateTime($endOfMonth->getTimestamp() * 1000);
    $query = [
        ['$unwind' => '$requests'],
        [
            '$match' => [
                'requests.dateCompleted' => [
                    '$gte' => $startDateTime,
                    '$lte' => $endDateTime
                ]
            ]
        ],
        ['$count' => 'totalRequests']
    ];
    $processedRequests = 0;
    $processedRequestsCursor = $requestsCollection->aggregate($query);
    foreach ($processedRequestsCursor as $result) {
        $processedRequests = $result->totalRequests;
    }
    return $processedRequests;
}

function processedRequestsQuery($startDate, $endDate) {
    $counter = 0;
    $processedRequests = [];
    $interval = [];
    $interval = getMonthRange($startDate, $endDate);
    foreach ($interval as $month) {
        $processedRequests[$counter++] = processedRequestsQueryByMonth($month);
    }
    return $processedRequests;
}
?>
