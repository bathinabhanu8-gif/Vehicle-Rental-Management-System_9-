<?php
/**
 * Database Seeder Script
 * Populates realistic vehicles, admin, customer, and booking records.
 * Can be run via CLI: php database/seed.php
 */

require_once __DIR__ . '/../config/database.php';

use MongoDB\BSON\UTCDateTime;

echo "===========================================\n";
echo "VS TRAVELLS - DATABASE SEEDER\n";
echo "===========================================\n";

$db = getDatabase();

// Clear existing collections if using local engine, or insert new
$nowMs = (int)(microtime(true) * 1000);
$adminHash = password_hash('Admin@123', PASSWORD_BCRYPT);
$customerHash = password_hash('Customer@123', PASSWORD_BCRYPT);

$users = [
    [
        '_id' => '6520a0000000000000000001',
        'name' => 'System Administrator',
        'email' => 'admin@vstravells.com',
        'phone' => '9876543210',
        'password' => $adminHash,
        'role' => 'admin',
        'license' => 'DL-ADMIN-001',
        'address' => 'VS Travells Headquarters, MG Road Hub, Bengaluru',
        'createdAt' => new UTCDateTime($nowMs),
        'updatedAt' => new UTCDateTime($nowMs)
    ],
    [
        '_id' => '6520a0000000000000000002',
        'name' => 'Bhanu Customer',
        'email' => 'customer@vstravells.com',
        'phone' => '9123456780',
        'password' => $customerHash,
        'role' => 'user',
        'license' => 'DL-04-2022-8921',
        'address' => 'Flat 402, Green Meadows, Indiranagar',
        'createdAt' => new UTCDateTime($nowMs),
        'updatedAt' => new UTCDateTime($nowMs)
    ]
];

$vehicles = [
    [
        '_id' => '6520b0000000000000000001',
        'name' => 'Maruti Suzuki Swift VXi',
        'type' => 'Car',
        'registrationNumber' => 'KA-01-MJ-1024',
        'pricePerDay' => 1800,
        'status' => 'Available',
        'seats' => 5,
        'fuel' => 'Petrol',
        'transmission' => 'Manual',
        'image' => 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=800&q=80',
        'description' => 'Agile, highly fuel-efficient 5-seater hatchback. Perfect for city commutes and weekend getaways with 22 km/l mileage.',
        'createdAt' => new UTCDateTime($nowMs),
        'updatedAt' => new UTCDateTime($nowMs)
    ],
    [
        '_id' => '6520b0000000000000000002',
        'name' => 'Hyundai Creta SX (O)',
        'type' => 'Car',
        'registrationNumber' => 'KA-03-NV-5542',
        'pricePerDay' => 3200,
        'status' => 'Available',
        'seats' => 5,
        'fuel' => 'Diesel',
        'transmission' => 'Automatic',
        'image' => 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&w=800&q=80',
        'description' => 'Premium compact SUV with panoramic sunroof, ventilated seats, Bose audio, and smooth automatic transmission.',
        'createdAt' => new UTCDateTime($nowMs),
        'updatedAt' => new UTCDateTime($nowMs)
    ],
    [
        '_id' => '6520b0000000000000000003',
        'name' => 'Mahindra Thar 4x4 Hard Top',
        'type' => 'Car',
        'registrationNumber' => 'KA-05-TR-9009',
        'pricePerDay' => 4200,
        'status' => 'Available',
        'seats' => 4,
        'fuel' => 'Diesel',
        'transmission' => 'Manual',
        'image' => 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&w=800&q=80',
        'description' => 'Iconic 4x4 off-road SUV. High ground clearance, all-terrain capability, rugged performance for mountain and adventure trips.',
        'createdAt' => new UTCDateTime($nowMs),
        'updatedAt' => new UTCDateTime($nowMs)
    ],
    [
        '_id' => '6520b0000000000000000004',
        'name' => 'Toyota Innova Crysta ZX',
        'type' => 'Car',
        'registrationNumber' => 'KA-04-IN-7788',
        'pricePerDay' => 4800,
        'status' => 'Available',
        'seats' => 7,
        'fuel' => 'Diesel',
        'transmission' => 'Automatic',
        'image' => 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=800&q=80',
        'description' => 'The gold standard for family and group touring. 7 luxury captain seats, unmatched highway stability, and ample luggage space.',
        'createdAt' => new UTCDateTime($nowMs),
        'updatedAt' => new UTCDateTime($nowMs)
    ],
    [
        '_id' => '6520b0000000000000000005',
        'name' => 'Tata Nexon EV Max',
        'type' => 'Car',
        'registrationNumber' => 'KA-51-EV-4321',
        'pricePerDay' => 2800,
        'status' => 'Available',
        'seats' => 5,
        'fuel' => 'EV',
        'transmission' => 'Automatic',
        'image' => 'https://images.unsplash.com/photo-1563720223185-11003d516935?auto=format&fit=crop&w=800&q=80',
        'description' => 'Zero-emission smart electric SUV with 437 km certified range, fast DC charging, and silent instant torque acceleration.',
        'createdAt' => new UTCDateTime($nowMs),
        'updatedAt' => new UTCDateTime($nowMs)
    ],
    [
        '_id' => '6520b0000000000000000006',
        'name' => 'Honda City ZX VTEC',
        'type' => 'Car',
        'registrationNumber' => 'KA-02-HC-6190',
        'pricePerDay' => 2600,
        'status' => 'Available',
        'seats' => 5,
        'fuel' => 'Petrol',
        'transmission' => 'Automatic',
        'image' => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=800&q=80',
        'description' => 'Executive sedan offering legendary comfort, lane watch camera, premium cabin, and punchy i-VTEC engine.',
        'createdAt' => new UTCDateTime($nowMs),
        'updatedAt' => new UTCDateTime($nowMs)
    ],
    [
        '_id' => '6520b0000000000000000007',
        'name' => 'Royal Enfield Classic 350 Stealth',
        'type' => 'Bike',
        'registrationNumber' => 'KA-01-RE-3500',
        'pricePerDay' => 1200,
        'status' => 'Available',
        'seats' => 2,
        'fuel' => 'Petrol',
        'transmission' => 'Manual',
        'image' => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=800&q=80',
        'description' => 'Timeless retro cruiser motorcycle with signature thump, dual-channel ABS, comfortable touring saddle, and alloy wheels.',
        'createdAt' => new UTCDateTime($nowMs),
        'updatedAt' => new UTCDateTime($nowMs)
    ],
    [
        '_id' => '6520b0000000000000000008',
        'name' => 'KTM Duke 390 BS6',
        'type' => 'Bike',
        'registrationNumber' => 'KA-03-DK-3939',
        'pricePerDay' => 1600,
        'status' => 'Available',
        'seats' => 2,
        'fuel' => 'Petrol',
        'transmission' => 'Manual',
        'image' => 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=800&q=80',
        'description' => 'High-performance street naked motorcycle with 43.5 PS horsepower, cornering ABS, quickshifter+, and TFT instrument display.',
        'createdAt' => new UTCDateTime($nowMs),
        'updatedAt' => new UTCDateTime($nowMs)
    ],
    [
        '_id' => '6520b0000000000000000009',
        'name' => 'Yamaha YZF-R15 V4',
        'type' => 'Bike',
        'registrationNumber' => 'KA-04-YR-1515',
        'pricePerDay' => 1100,
        'status' => 'Available',
        'seats' => 2,
        'fuel' => 'Petrol',
        'transmission' => 'Manual',
        'image' => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=800&q=80',
        'description' => 'Track-bred supersport bike with upside-down front forks, variable valve actuation (VVA), and aerodynamic fairing.',
        'createdAt' => new UTCDateTime($nowMs),
        'updatedAt' => new UTCDateTime($nowMs)
    ],
    [
        '_id' => '6520b0000000000000000010',
        'name' => 'Ather 450X Gen 3',
        'type' => 'Bike',
        'registrationNumber' => 'KA-05-AT-4500',
        'pricePerDay' => 750,
        'status' => 'Available',
        'seats' => 2,
        'fuel' => 'EV',
        'transmission' => 'Automatic',
        'image' => 'https://images.unsplash.com/photo-1558980394-4c7c9299fe96?auto=format&fit=crop&w=800&q=80',
        'description' => 'Futuristic smart electric scooter with Warp mode (0-40 in 3.3s), Google Maps onboard navigation, and 105 km true range.',
        'createdAt' => new UTCDateTime($nowMs),
        'updatedAt' => new UTCDateTime($nowMs)
    ],
    [
        '_id' => '6520b0000000000000000011',
        'name' => 'Honda Activa 6G DLX',
        'type' => 'Bike',
        'registrationNumber' => 'KA-02-AC-6612',
        'pricePerDay' => 600,
        'status' => 'Available',
        'seats' => 2,
        'fuel' => 'Petrol',
        'transmission' => 'Automatic',
        'image' => 'https://images.unsplash.com/photo-1558980664-769d59546b3d?auto=format&fit=crop&w=800&q=80',
        'description' => 'India\'s most trusted automatic scooter. Highly reliable, easy handling, telescopic suspension, and comfortable city ride.',
        'createdAt' => new UTCDateTime($nowMs),
        'updatedAt' => new UTCDateTime($nowMs)
    ],
    [
        '_id' => '6520b0000000000000000012',
        'name' => 'BMW 330i M Sport',
        'type' => 'Car',
        'registrationNumber' => 'KA-01-BM-3300',
        'pricePerDay' => 7500,
        'status' => 'Available',
        'seats' => 5,
        'fuel' => 'Petrol',
        'transmission' => 'Automatic',
        'image' => 'https://images.unsplash.com/photo-1555215695-3004980ad54e?auto=format&fit=crop&w=800&q=80',
        'description' => 'Ultimate luxury sports sedan. 258 bhp TwinPower Turbo engine, 50:50 weight distribution, Harman Kardon surround audio.',
        'createdAt' => new UTCDateTime($nowMs),
        'updatedAt' => new UTCDateTime($nowMs)
    ]
];

$today = new DateTimeImmutable('today');
$startDemo = $today->modify('+2 days');
$endDemo = $today->modify('+5 days');

$bookings = [
    [
        '_id' => '6520c0000000000000000001',
        'userId' => '6520a0000000000000000002',
        'vehicleId' => '6520b0000000000000000001',
        'vehicleName' => 'Maruti Suzuki Swift VXi',
        'vehicleType' => 'Car',
        'pricePerDay' => 1800,
        'startDate' => new UTCDateTime($startDemo->getTimestamp() * 1000),
        'endDate' => new UTCDateTime($endDemo->getTimestamp() * 1000),
        'pickupLocation' => 'Bengaluru Airport Terminal 1',
        'returnLocation' => 'Indiranagar Hub',
        'totalDays' => 4,
        'totalPrice' => 7200,
        'status' => 'Confirmed',
        'paymentStatus' => 'Paid (Demo)',
        'paymentMethod' => 'UPI',
        'paymentReference' => 'VST982187342',
        'createdAt' => new UTCDateTime($nowMs),
        'updatedAt' => new UTCDateTime($nowMs),
        'paidAt' => new UTCDateTime($nowMs)
    ]
];

// Check if LocalDatabase or Mongo
if ($db instanceof \ResilientDB\LocalDatabase) {
    $db->seedInitialData();
    echo "✓ Resilient Local Engine seeded successfully!\n";
} else {
    foreach ($users as $u) {
        $existing = $db->users->findOne(['email' => $u['email']]);
        if (!$existing) $db->users->insertOne($u);
    }
    foreach ($vehicles as $v) {
        $existing = $db->vehicles->findOne(['registrationNumber' => $v['registrationNumber']]);
        if (!$existing) $db->vehicles->insertOne($v);
    }
    echo "✓ Cloud MongoDB Atlas collections populated successfully!\n";
}

echo "Admin Account:    admin@vstravells.com / Admin@123\n";
echo "Customer Account: customer@vstravells.com / Customer@123\n";
echo "Vehicles Added:   " . count($vehicles) . "\n";
echo "===========================================\n";
