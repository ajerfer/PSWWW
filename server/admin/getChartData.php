<?php

include_once 'mongoQuery.php';

$startDate = $_GET['startDate'];
$endDate = $_GET['endDate'];

$newOffers = newOffersQuery($startDate, $endDate);
$processedOffers = processedOffersQuery($startDate,$endDate);
$newRequests = newRequestsQuery($startDate,$endDate);
$processedRequests =  processedRequestsQuery($startDate,$endDate);

$currentDate = new DateTime($startDate);
$endDateTime = new DateTime($endDate);
$monthLabels = [];

while ($currentDate <= $endDateTime) {
    $monthLabels[] = $currentDate->format('Y-m');
    $currentDate->modify('+1 month');
}

$data = [
    'labels' => $monthLabels,
    'newOffers' => $newOffers,
    'processedOffers' => $processedOffers,
    'newRequests' => $newRequests,
    'processedRequests' => $processedRequests
];

echo json_encode($data);
?>