<?php
require_once __DIR__ . '/../../middleware/jsonMiddleware.php';
require_once __DIR__ . '/../../middleware/requestLogger.php';
require_once __DIR__ . '/../../middleware/corsMiddleware.php';
require_once __DIR__ . '/../../utils/response.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../algorithms/MergeSort.php';

use DAA\MergeSort;

setJsonResponse();
handleCors();
logRequest();

if ($_SERVER['REQUEST_METHOD'] !== 'GET' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Method not allowed.', null, 405);
}

try {
    $db = getDatabase();
    $key = trim($_GET['key'] ?? $_POST['key'] ?? 'pricePerDay');
    $order = trim($_GET['order'] ?? $_POST['order'] ?? 'ASC');

    $allowedKeys = ['pricePerDay', 'seats', 'name'];
    if (!in_array($key, $allowedKeys, true)) {
        $key = 'pricePerDay';
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

    // Execute Merge Sort Algorithm
    $result = MergeSort::sort($vehicles, $key, $order);

    sendResponse(true, 'Vehicles sorted successfully using Merge Sort', $result);
} catch (Throwable $e) {
    error_log('MergeSort API error: ' . $e->getMessage());
    sendResponse(false, 'Sorting algorithm failed.', null, 500);
}
