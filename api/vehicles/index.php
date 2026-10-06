<?php
require_once '../../middleware/jsonMiddleware.php';
require_once '../../middleware/requestLogger.php';
require_once '../../middleware/corsMiddleware.php';
require_once '../../utils/response.php';
require_once '../../config/database.php';
setJsonResponse(); handleCors(); logRequest();
if ($_SERVER['REQUEST_METHOD'] !== 'GET') sendResponse(false, 'Method not allowed. Use GET.', null, 405);
try {
    $filter = [];
    $search = trim($_GET['search'] ?? '');
    $type = trim($_GET['type'] ?? '');
    $status = trim($_GET['status'] ?? '');
    if ($search !== '') $filter['name'] = ['$regex' => preg_quote($search, '/'), '$options' => 'i'];
    if ($type !== '') {
        if (!in_array($type, ['Car','Bike'], true)) sendResponse(false, 'Invalid vehicle type.', null, 400);
        $filter['type'] = $type;
    }
    if ($status !== '') {
        if (!in_array($status, ['Available','Unavailable'], true)) sendResponse(false, 'Invalid vehicle status.', null, 400);
        $filter['status'] = $status;
    }
    $cursor = getDatabase()->vehicles->find($filter, ['sort' => ['name' => 1]]);
    $list = [];
    foreach ($cursor as $vehicle) {
        $list[] = [
            'id' => (string)$vehicle['_id'],
            'name' => $vehicle['name'] ?? '',
            'type' => $vehicle['type'] ?? '',
            'pricePerDay' => (float)($vehicle['pricePerDay'] ?? 0),
            'status' => $vehicle['status'] ?? ''
        ];
    }
    sendResponse(true, 'Vehicles retrieved successfully from MongoDB', $list);
} catch (Throwable $e) {
    error_log('Vehicle retrieval error: '.$e->getMessage());
    sendResponse(false, 'Failed to retrieve vehicles from database.', null, 500);
}
