<?php
require_once __DIR__ . '/../../middleware/jsonMiddleware.php';
require_once __DIR__ . '/../../middleware/requestLogger.php';
require_once __DIR__ . '/../../middleware/corsMiddleware.php';
require_once __DIR__ . '/../../utils/response.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../algorithms/MergeSort.php';
require_once __DIR__ . '/../../algorithms/BinarySearch.php';

use DAA\MergeSort;
use DAA\BinarySearch;

setJsonResponse();
handleCors();
logRequest();

if ($_SERVER['REQUEST_METHOD'] !== 'GET' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Method not allowed.', null, 405);
}

try {
    $db = getDatabase();
    $target = trim($_GET['query'] ?? $_POST['query'] ?? '');
    $key = trim($_GET['key'] ?? $_POST['key'] ?? 'name');

    if ($target === '') {
        sendResponse(false, 'Please provide a search target query.', null, 400);
    }

    $vehiclesRaw = iterator_to_array($db->vehicles->find([]));
    $vehicles = [];
    foreach ($vehiclesRaw as $v) {
        $vehicles[] = [
            'id' => (string)$v['_id'],
            'name' => $v['name'] ?? '',
            'type' => $v['type'] ?? 'Car',
            'registrationNumber' => $v['registrationNumber'] ?? '',
            'pricePerDay' => (float)($v['pricePerDay'] ?? 0),
            'status' => $v['status'] ?? 'Available',
            'image' => $v['image'] ?? '',
            'seats' => (int)($v['seats'] ?? (($v['type'] ?? '') === 'Bike' ? 2 : 5)),
            'fuel' => $v['fuel'] ?? 'Petrol',
            'transmission' => $v['transmission'] ?? 'Manual',
            'description' => $v['description'] ?? ''
        ];
    }

    // Binary Search requires a pre-sorted dataset: sort by search key first using MergeSort
    $sortedRes = MergeSort::sort($vehicles, $key, 'ASC');
    $sortedList = $sortedRes['sortedData'];

    // Execute Binary Search
    $searchResult = BinarySearch::search($sortedList, $key, $target);

    // Also include prefix matches for friendly UI experience
    $prefixMatches = BinarySearch::searchPrefix($sortedList, $key, $target);

    $searchResult['prefixMatches'] = $prefixMatches;
    $searchResult['sortedDataset'] = $sortedList;

    sendResponse(true, 'Binary search executed successfully', $searchResult);
} catch (Throwable $e) {
    error_log('BinarySearch API error: ' . $e->getMessage());
    sendResponse(false, 'Search algorithm failed.', null, 500);
}
