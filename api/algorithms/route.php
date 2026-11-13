<?php
require_once __DIR__ . '/../../middleware/jsonMiddleware.php';
require_once __DIR__ . '/../../middleware/requestLogger.php';
require_once __DIR__ . '/../../middleware/corsMiddleware.php';
require_once __DIR__ . '/../../utils/response.php';
require_once __DIR__ . '/../../algorithms/DijkstraRouteOptimizer.php';

use DAA\DijkstraRouteOptimizer;

setJsonResponse();
handleCors();
logRequest();

$input = json_decode(file_get_contents('php://input'), true) ?? [];
$source = trim($_GET['source'] ?? $input['source'] ?? 'BLR_AIRPORT');
$destination = trim($_GET['destination'] ?? $input['destination'] ?? 'ELECTRONIC_CITY');

try {
    // If client just wants the list of hubs
    if (isset($_GET['action']) && $_GET['action'] === 'hubs') {
        sendResponse(true, 'Hubs retrieved', DijkstraRouteOptimizer::getHubs());
    }

    $result = DijkstraRouteOptimizer::computeShortestPath($source, $destination);

    sendResponse(true, 'Shortest path computed successfully using Dijkstra\'s Algorithm', $result);
} catch (Throwable $e) {
    error_log('Dijkstra API error: ' . $e->getMessage());
    sendResponse(false, 'Route optimization algorithm failed.', null, 500);
}
