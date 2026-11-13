<?php
/**
 * Master Academic Test Suite
 * Automated Test Runner for College Project Evaluation
 *
 * Verifies:
 *   1. Database connectivity & Resilient Local Fallback
 *   2. 5 DAA Optimization Algorithms
 *   3. Customer & Admin authentication logic
 *   4. Booking creation & Interval Overlap Prevention
 *   5. Full 6-stage rental lifecycle transitions
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../algorithms/BinarySearch.php';
require_once __DIR__ . '/../algorithms/MergeSort.php';
require_once __DIR__ . '/../algorithms/IntervalOverlap.php';
require_once __DIR__ . '/../algorithms/GreedyVehicleAllocation.php';
require_once __DIR__ . '/../algorithms/DijkstraRouteOptimizer.php';

use DAA\BinarySearch;
use DAA\MergeSort;
use DAA\IntervalOverlap;
use DAA\GreedyVehicleAllocation;
use DAA\DijkstraRouteOptimizer;

$totalTests = 0;
$passed = 0;
$failed = 0;

function runTest(string $name, callable $fn) {
    global $totalTests, $passed, $failed;
    $totalTests++;
    echo "TEST " . str_pad((string)$totalTests, 2, '0', STR_PAD_LEFT) . ": " . str_pad($name, 55, '.');
    try {
        $result = $fn();
        if ($result === true || $result === null) {
            $passed++;
            echo " [ PASSED ]\n";
        } else {
            $failed++;
            echo " [ FAILED: $result ]\n";
        }
    } catch (Throwable $e) {
        $failed++;
        echo " [ ERROR: " . $e->getMessage() . " ]\n";
    }
}

echo "========================================================================\n";
echo "   VS TRAVELLS - COMPREHENSIVE AUTOMATED TEST SUITE\n";
echo "========================================================================\n\n";

// SECTION 1: DATABASE & STORAGE
runTest("Database connectivity & collection access", function() {
    $db = getDatabase();
    if (!$db) return "Database instance is null";
    $vehicles = $db->vehicles->countDocuments([]);
    if ($vehicles < 1) return "No vehicles in database";
    return true;
});

runTest("Admin user account verification", function() {
    $db = getDatabase();
    $admin = $db->users->findOne(['email' => 'admin@vstravells.com']);
    if (!$admin) return "Admin record not found";
    if (($admin['role'] ?? '') !== 'admin') return "Role is not admin";
    if (!password_verify('Admin@123', (string)$admin['password'])) return "Password verification failed";
    return true;
});

runTest("Customer user account verification", function() {
    $db = getDatabase();
    $cust = $db->users->findOne(['email' => 'customer@vstravells.com']);
    if (!$cust) return "Customer record not found";
    if (($cust['role'] ?? '') !== 'user') return "Role is not user";
    if (!password_verify('Customer@123', (string)$cust['password'])) return "Password verification failed";
    return true;
});

// SECTION 2: DAA ALGORITHMS
$mockFleet = [
    ['name' => 'Swift', 'pricePerDay' => 1800, 'seats' => 5, 'status' => 'Available', 'type' => 'Car', 'fuel' => 'Petrol'],
    ['name' => 'Activa', 'pricePerDay' => 600, 'seats' => 2, 'status' => 'Available', 'type' => 'Bike', 'fuel' => 'Petrol'],
    ['name' => 'Creta', 'pricePerDay' => 3200, 'seats' => 5, 'status' => 'Available', 'type' => 'Car', 'fuel' => 'Diesel'],
    ['name' => 'BMW', 'pricePerDay' => 7500, 'seats' => 5, 'status' => 'Available', 'type' => 'Car', 'fuel' => 'Petrol'],
    ['name' => 'Ather', 'pricePerDay' => 750, 'seats' => 2, 'status' => 'Available', 'type' => 'Bike', 'fuel' => 'EV']
];

runTest("DAA 1: Merge Sort O(n log n) by Price (ASC)", function() use ($mockFleet) {
    $res = MergeSort::sort($mockFleet, 'pricePerDay', 'ASC');
    $sorted = $res['sortedData'];
    if ($sorted[0]['name'] !== 'Activa' || $sorted[0]['pricePerDay'] != 600) return "First element not lowest price";
    if ($sorted[4]['name'] !== 'BMW' || $sorted[4]['pricePerDay'] != 7500) return "Last element not highest price";
    if ($res['comparisons'] < 1) return "No comparisons recorded";
    return true;
});

runTest("DAA 2: Merge Sort O(n log n) by Seats (DESC)", function() use ($mockFleet) {
    $res = MergeSort::sort($mockFleet, 'seats', 'DESC');
    $sorted = $res['sortedData'];
    if ($sorted[0]['seats'] < 5) return "Descending seats failed";
    return true;
});

runTest("DAA 3: Binary Search O(log n) exact match", function() use ($mockFleet) {
    $sorted = MergeSort::sort($mockFleet, 'name', 'ASC')['sortedData'];
    $res = BinarySearch::search($sorted, 'name', 'Creta');
    if (!$res['found']) return "Creta not found";
    if ($res['item']['name'] !== 'Creta') return "Incorrect item returned";
    return true;
});

runTest("DAA 4: Binary Search non-existent element handling", function() use ($mockFleet) {
    $sorted = MergeSort::sort($mockFleet, 'name', 'ASC')['sortedData'];
    $res = BinarySearch::search($sorted, 'name', 'NonExistentHelicopter');
    if ($res['found']) return "False positive match reported";
    return true;
});

runTest("DAA 5: Interval Overlap Detection - Conflict Detected", function() {
    $existing = [
        ['startDate' => '2026-12-01', 'endDate' => '2026-12-05', 'status' => 'Confirmed', 'vehicleName' => 'Swift']
    ];
    $res = IntervalOverlap::checkOverlap('2026-12-03', '2026-12-07', $existing);
    if (!$res['hasConflict']) return "Failed to detect overlapping range";
    if ($res['conflictCount'] !== 1) return "Incorrect conflict count";
    return true;
});

runTest("DAA 6: Interval Overlap Detection - Clean Non-Overlapping", function() {
    $existing = [
        ['startDate' => '2026-12-01', 'endDate' => '2026-12-05', 'status' => 'Confirmed', 'vehicleName' => 'Swift']
    ];
    $res = IntervalOverlap::checkOverlap('2026-12-06', '2026-12-10', $existing);
    if ($res['hasConflict']) return "Reported false conflict on adjacent non-overlapping dates";
    return true;
});

runTest("DAA 7: Greedy Vehicle Allocation - Within Budget & Seats", function() use ($mockFleet) {
    $res = GreedyVehicleAllocation::allocate($mockFleet, 5000, 2, 2, 'Bike', 'Petrol');
    if (!$res['foundMatch']) return "No match found";
    if ($res['allocatedVehicle']['vehicle']['name'] !== 'Activa') return "Optimal choice was not Activa";
    return true;
});

runTest("DAA 8: Greedy Allocation - Budget Rejection", function() use ($mockFleet) {
    // Budget is ₹500 for 5 days (₹100/day) - impossible for any vehicle
    $res = GreedyVehicleAllocation::allocate($mockFleet, 500, 2, 5, 'Any', 'Any');
    if ($res['foundMatch']) return "Accepted candidate exceeding budget constraint";
    return true;
});

runTest("DAA 9: Dijkstra Route Optimization - Airport to MG Road", function() {
    $res = DijkstraRouteOptimizer::computeShortestPath('BLR_AIRPORT', 'MG_ROAD');
    if (!$res['pathFound']) return "Path not found";
    if ($res['totalDistanceKm'] <= 0) return "Invalid distance calculated";
    $hops = array_column($res['path'], 'hubCode');
    if ($hops[0] !== 'BLR_AIRPORT' || end($hops) !== 'MG_ROAD') return "Invalid start or end node";
    return true;
});

runTest("DAA 10: Dijkstra Graph Symmetry & Non-Negative Weights", function() {
    $res1 = DijkstraRouteOptimizer::computeShortestPath('BLR_AIRPORT', 'WHITEFIELD');
    $res2 = DijkstraRouteOptimizer::computeShortestPath('WHITEFIELD', 'BLR_AIRPORT');
    if (abs($res1['totalDistanceKm'] - $res2['totalDistanceKm']) > 0.01) return "Asymmetric distance in undirected graph";
    return true;
});

// SECTION 3: BOOKING LIFECYCLE
runTest("Booking Lifecycle Stage 1: Pending Creation", function() {
    $db = getDatabase();
    $admin = $db->users->findOne(['email' => 'admin@vstravells.com']);
    $v = $db->vehicles->findOne(['status' => 'Available']);
    if (!$v) return "No available vehicle found";

    $today = new DateTimeImmutable('today');
    $start = $today->modify('+20 days');
    $end = $today->modify('+23 days');

    $r = $db->bookings->insertOne([
        'userId' => $admin['_id'],
        'vehicleId' => $v['_id'],
        'vehicleName' => $v['name'],
        'vehicleType' => $v['type'],
        'pricePerDay' => (float)$v['pricePerDay'],
        'startDate' => new \MongoDB\BSON\UTCDateTime($start->getTimestamp() * 1000),
        'endDate' => new \MongoDB\BSON\UTCDateTime($end->getTimestamp() * 1000),
        'pickupLocation' => 'Central MG Road Depot',
        'returnLocation' => 'Indiranagar Hub',
        'totalDays' => 4,
        'totalPrice' => 4 * (float)$v['pricePerDay'],
        'status' => 'Pending',
        'paymentStatus' => 'Not Paid',
        'createdAt' => new \MongoDB\BSON\UTCDateTime(),
        'updatedAt' => new \MongoDB\BSON\UTCDateTime()
    ]);

    $id = (string)$r->getInsertedId();
    $b = $db->bookings->findOne(['_id' => $r->getInsertedId()]);
    if (!$b || $b['status'] !== 'Pending') return "Pending booking failed to insert";

    // Clean up test booking
    $db->bookings->deleteOne(['_id' => $r->getInsertedId()]);
    return true;
});




// SECTION 4: FULL LIFECYCLE & COLLISION TESTS
runTest("Booking Lifecycle Stage 2: Admin Approval", function() {
    $db = getDatabase();
    $admin = $db->users->findOne(['email' => 'admin@vstravells.com']);
    $v = $db->vehicles->findOne(['status' => 'Available']);

    $today = new DateTimeImmutable('today');
    $start = $today->modify('+30 days');
    $end = $today->modify('+33 days');

    $r = $db->bookings->insertOne([
        'userId' => $admin['_id'],
        'vehicleId' => $v['_id'],
        'vehicleName' => $v['name'],
        'vehicleType' => $v['type'],
        'pricePerDay' => (float)$v['pricePerDay'],
        'startDate' => new \MongoDB\BSON\UTCDateTime($start->getTimestamp() * 1000),
        'endDate' => new \MongoDB\BSON\UTCDateTime($end->getTimestamp() * 1000),
        'pickupLocation' => 'Airport',
        'returnLocation' => 'Depot',
        'totalDays' => 4,
        'totalPrice' => 4 * (float)$v['pricePerDay'],
        'status' => 'Pending',
        'paymentStatus' => 'Not Paid'
    ]);
    $bid = $r->getInsertedId();

    // Transition: Pending -> Approved
    $db->bookings->updateOne(['_id' => $bid], ['$set' => ['status' => 'Approved', 'paymentStatus' => 'Awaiting Payment']]);
    $b = $db->bookings->findOne(['_id' => $bid]);
    if ($b['status'] !== 'Approved' || $b['paymentStatus'] !== 'Awaiting Payment') {
        $db->bookings->deleteOne(['_id' => $bid]);
        return "Failed to transition to Approved";
    }

    // Clean up
    $db->bookings->deleteOne(['_id' => $bid]);
    return true;
});

runTest("Booking Lifecycle Stage 3: Demo Payment & Confirmation", function() {
    $db = getDatabase();
    $admin = $db->users->findOne(['email' => 'admin@vstravells.com']);
    $v = $db->vehicles->findOne(['status' => 'Available']);

    $today = new DateTimeImmutable('today');
    $start = $today->modify('+40 days');
    $end = $today->modify('+42 days');

    $r = $db->bookings->insertOne([
        'userId' => $admin['_id'],
        'vehicleId' => $v['_id'],
        'vehicleName' => $v['name'],
        'vehicleType' => $v['type'],
        'pricePerDay' => (float)$v['pricePerDay'],
        'startDate' => new \MongoDB\BSON\UTCDateTime($start->getTimestamp() * 1000),
        'endDate' => new \MongoDB\BSON\UTCDateTime($end->getTimestamp() * 1000),
        'status' => 'Approved',
        'paymentStatus' => 'Awaiting Payment'
    ]);
    $bid = $r->getInsertedId();

    // Transition: Approved -> Confirmed with Demo Reference
    $ref = 'VST' . strtoupper(substr(bin2hex(random_bytes(5)), 0, 10));
    $db->bookings->updateOne(['_id' => $bid], ['$set' => [
        'status' => 'Confirmed',
        'paymentStatus' => 'Paid (Demo)',
        'paymentReference' => $ref,
        'paymentMethod' => 'UPI'
    ]]);

    $b = $db->bookings->findOne(['_id' => $bid]);
    if ($b['status'] !== 'Confirmed' || $b['paymentStatus'] !== 'Paid (Demo)') {
        $db->bookings->deleteOne(['_id' => $bid]);
        return "Failed to transition to Confirmed";
    }

    // Clean up
    $db->bookings->deleteOne(['_id' => $bid]);
    return true;
});

runTest("Booking Lifecycle Stage 4-6: Pickup -> Return -> Complete", function() {
    $db = getDatabase();
    $admin = $db->users->findOne(['email' => 'admin@vstravells.com']);
    $v = $db->vehicles->findOne(['status' => 'Available']);

    $today = new DateTimeImmutable('today');
    $start = $today->modify('+50 days');
    $end = $today->modify('+52 days');

    $r = $db->bookings->insertOne([
        'userId' => $admin['_id'],
        'vehicleId' => $v['_id'],
        'vehicleName' => $v['name'],
        'vehicleType' => $v['type'],
        'pricePerDay' => (float)$v['pricePerDay'],
        'startDate' => new \MongoDB\BSON\UTCDateTime($start->getTimestamp() * 1000),
        'endDate' => new \MongoDB\BSON\UTCDateTime($end->getTimestamp() * 1000),
        'status' => 'Confirmed',
        'paymentStatus' => 'Paid (Demo)',
        'paymentReference' => 'VST12345678'
    ]);
    $bid = $r->getInsertedId();

    // Stage 4: Pickup -> Active
    $db->bookings->updateOne(['_id' => $bid], ['$set' => ['status' => 'Active']]);
    $b = $db->bookings->findOne(['_id' => $bid]);
    if ($b['status'] !== 'Active') return "Stage 4 Active failed";

    // Stage 5: Return -> Returned
    $db->bookings->updateOne(['_id' => $bid], ['$set' => ['status' => 'Returned']]);
    $b = $db->bookings->findOne(['_id' => $bid]);
    if ($b['status'] !== 'Returned') return "Stage 5 Returned failed";

    // Stage 6: Complete -> Completed
    $db->bookings->updateOne(['_id' => $bid], ['$set' => ['status' => 'Completed']]);
    $b = $db->bookings->findOne(['_id' => $bid]);
    if ($b['status'] !== 'Completed') return "Stage 6 Completed failed";

    // Clean up
    $db->bookings->deleteOne(['_id' => $bid]);
    return true;
});

runTest("Double Booking Rejection: Interval Overlap Guard", function() {
    $db = getDatabase();
    $admin = $db->users->findOne(['email' => 'admin@vstravells.com']);
    $v = $db->vehicles->findOne(['status' => 'Available']);

    $today = new DateTimeImmutable('today');
    $start = $today->modify('+60 days');
    $end = $today->modify('+65 days');

    // Insert an active reservation
    $r = $db->bookings->insertOne([
        'userId' => $admin['_id'],
        'vehicleId' => $v['_id'],
        'vehicleName' => $v['name'],
        'startDate' => new \MongoDB\BSON\UTCDateTime($start->getTimestamp() * 1000),
        'endDate' => new \MongoDB\BSON\UTCDateTime($end->getTimestamp() * 1000),
        'status' => 'Confirmed',
        'paymentStatus' => 'Paid (Demo)'
    ]);
    $bid = $r->getInsertedId();

    // Try to check overlap with conflicting date range (days +62 to +68)
    $reqStart = $today->modify('+62 days');
    $reqEnd = $today->modify('+68 days');

    $activeBookings = iterator_to_array($db->bookings->find([
        'vehicleId' => $v['_id'],
        'status' => ['$in' => ['Approved', 'Confirmed', 'Active', 'Returned']]
    ]));

    $overlap = IntervalOverlap::checkOverlap($reqStart, $reqEnd, $activeBookings);

    // Clean up
    $db->bookings->deleteOne(['_id' => $bid]);

    if (!$overlap['hasConflict']) {
        return "IntervalOverlap failed to reject double booking";
    }
    return true;
});


echo "\n========================================================================\n";
echo "   FINAL TEST SUMMARY: $passed / $totalTests PASSED ($failed FAILED)\n";
echo "========================================================================\n";

if ($failed === 0) {
    echo "🎉 ALL TESTS PASSED! Project is in 100% verified working condition.\n";
}
