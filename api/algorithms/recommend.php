<?php
require_once __DIR__ . '/../../middleware/jsonMiddleware.php';
require_once __DIR__ . '/../../middleware/requestLogger.php';
require_once __DIR__ . '/../../middleware/corsMiddleware.php';
require_once __DIR__ . '/../../utils/response.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../algorithms/GreedyVehicleAllocation.php';

use DAA\GreedyVehicleAllocation;

setJsonResponse();
handleCors();
logRequest();

$input = json_decode(file_get_contents('php://input'), true) ?? [];
$budget = (float)($_GET['budget'] ?? $input['budget'] ?? 5000);
$seats = (int)($_GET['seats'] ?? $input['seats'] ?? 4);
$days = (int)($_GET['days'] ?? $input['days'] ?? 3);
$type = trim($_GET['type'] ?? $input['type'] ?? 'Any');
$fuel = trim($_GET['fuel'] ?? $input['fuel'] ?? 'Any');

try {
    $db = getDatabase();
    $fleetRaw = iterator_to_array($db->vehicles->find([]));
    $fleet = [];
    foreach ($fleetRaw as $v) {
        $fleet[] = [
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

    // Run Greedy Allocation Algorithm
    $recommendation = GreedyVehicleAllocation::allocate($fleet, $budget, $seats, $days, $type, $fuel);

    sendResponse(
        true,
        $recommendation['foundMatch'] ? 'Optimal vehicle allocated using Greedy strategy.' : 'No available vehicle matches your criteria within budget.',
        $recommendation
    );
} catch (Throwable $e) {
    error_log('Greedy Allocation API error: ' . $e->getMessage());
    sendResponse(false, 'Greedy allocation algorithm failed.', null, 500);
}
