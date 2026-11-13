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

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendResponse(false, 'Method not allowed.', null, 405);
}

try {
    $db = getDatabase();
    $filter = [];
    $search = trim($_GET['search'] ?? '');
    $type = trim($_GET['type'] ?? '');
    $status = trim($_GET['status'] ?? '');
    $sortBy = trim($_GET['sort'] ?? ''); // e.g. price_asc, price_desc, seats_desc, name_asc

    if ($type !== '') {
        $filter['type'] = $type;
    }
    if ($status !== '') {
        $filter['status'] = $status;
    }

    $rawList = iterator_to_array($db->vehicles->find($filter));
    $list = [];
    foreach ($rawList as $v) {
        $list[] = [
            'id' => (string)$v['_id'],
            'name' => $v['name'] ?? '',
            'type' => $v['type'] ?? 'Car',
            'registrationNumber' => $v['registrationNumber'] ?? '',
            'pricePerDay' => (float)($v['pricePerDay'] ?? 0),
            'status' => $v['status'] ?? 'Available',
            'image' => $v['image'] ?? '',
            'seats' => (int)($v['seats'] ?? ((($v['type'] ?? '') === 'Bike') ? 2 : 5)),
            'fuel' => $v['fuel'] ?? 'Petrol',
            'transmission' => $v['transmission'] ?? 'Manual',
            'description' => $v['description'] ?? 'Comfortable and reliable rental vehicle.'
        ];
    }

    // Apply Binary Search / Search Filter
    if ($search !== '') {
        $sortedForSearch = MergeSort::sort($list, 'name', 'ASC')['sortedData'];
        $exactMatch = BinarySearch::search($sortedForSearch, 'name', $search);
        if ($exactMatch['found']) {
            $prefixMatches = BinarySearch::searchPrefix($sortedForSearch, 'name', $search);
            $list = $prefixMatches;
        } else {
            $list = BinarySearch::searchPrefix($sortedForSearch, 'name', $search);
        }
    }

    // Apply Merge Sort if sort option requested
    if ($sortBy !== '') {
        if ($sortBy === 'price_asc') {
            $list = MergeSort::sort($list, 'pricePerDay', 'ASC')['sortedData'];
        } elseif ($sortBy === 'price_desc') {
            $list = MergeSort::sort($list, 'pricePerDay', 'DESC')['sortedData'];
        } elseif ($sortBy === 'seats_desc') {
            $list = MergeSort::sort($list, 'seats', 'DESC')['sortedData'];
        } elseif ($sortBy === 'name_asc') {
            $list = MergeSort::sort($list, 'name', 'ASC')['sortedData'];
        }
    }

    sendResponse(true, 'Vehicles retrieved', $list);
} catch (Throwable $e) {
    error_log('Vehicles API error: ' . $e->getMessage());
    sendResponse(false, 'Unable to load vehicles.', null, 500);
}
