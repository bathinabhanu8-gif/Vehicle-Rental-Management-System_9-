<?php
require_once __DIR__ . '/../../middleware/jsonMiddleware.php';
require_once __DIR__ . '/../../middleware/requestLogger.php';
require_once __DIR__ . '/../../middleware/corsMiddleware.php';
require_once __DIR__ . '/../../middleware/authMiddleware.php';
require_once __DIR__ . '/../../utils/response.php';
require_once __DIR__ . '/../../config/database.php';

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

setJsonResponse();
handleCors();
logRequest();
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    sendResponse(false, 'Method not allowed.', null, 405);
}

$d = json_decode(file_get_contents('php://input'), true);
if (!is_array($d)) {
    sendResponse(false, 'Invalid request data.', null, 400);
}

$id = trim($d['id'] ?? '');
$name = trim($d['name'] ?? '');
$type = trim($d['type'] ?? '');
$reg = trim($d['registrationNumber'] ?? '');
$price = $d['pricePerDay'] ?? 0;
$status = trim($d['status'] ?? 'Available');
$image = trim($d['image'] ?? '');
$seats = (int)($d['seats'] ?? 0);
$fuel = trim($d['fuel'] ?? '');
$trans = trim($d['transmission'] ?? '');
$desc = trim($d['description'] ?? '');

if ($id === '' || $name === '' || !in_array($type, ['Car', 'Bike'], true) || !is_numeric($price) || (float)$price <= 0) {
    sendResponse(false, 'Enter a valid vehicle ID, name, type and price.', null, 400);
}

if (!in_array($status, ['Available', 'Booked', 'On Rent', 'Maintenance'], true)) {
    sendResponse(false, 'Invalid vehicle status.', null, 400);
}

if ($seats < 1) {
    $seats = $type === 'Bike' ? 2 : 5;
}

try {
    $vid = (preg_match('/^[a-f0-9]{24}$/i', $id)) ? new ObjectId($id) : $id;
    $db = getDatabase();

    $db->vehicles->updateOne(
        ['_id' => $vid],
        ['$set' => [
            'name' => $name,
            'type' => $type,
            'registrationNumber' => $reg,
            'pricePerDay' => (float)$price,
            'status' => $status,
            'image' => $image,
            'seats' => $seats,
            'fuel' => $fuel ?: 'Petrol',
            'transmission' => $trans ?: 'Manual',
            'description' => $desc,
            'updatedAt' => new UTCDateTime()
        ]]
    );

    sendResponse(true, 'Vehicle updated successfully.');
} catch (Throwable $e) {
    error_log('Vehicle update error: ' . $e->getMessage());
    sendResponse(false, 'Failed to update vehicle.', null, 500);
}
