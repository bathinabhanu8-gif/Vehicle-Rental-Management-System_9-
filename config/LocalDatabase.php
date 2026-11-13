<?php
/**
 * Resilient Local Data Store Adapter
 * Provides high-availability fallback when Cloud MongoDB Atlas is unreachable.
 * Implements the MongoDB Collection & Database interface so all controllers,
 * algorithms, and APIs work seamlessly offline and online.
 */

namespace ResilientDB;

use DateTimeImmutable;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class MockObjectId {
    private string $id;

    public function __construct(?string $id = null) {
        if ($id !== null && preg_match('/^[a-f0-9]{24}$/i', $id)) {
            $this->id = strtolower($id);
        } else {
            $this->id = bin2hex(random_bytes(12));
        }
    }

    public function __toString(): string {
        return $this->id;
    }

    public function jsonSerialize(): string {
        return $this->id;
    }
}

class MockUTCDateTime {
    private int $milliseconds;

    public function __construct(null|int|DateTimeImmutable $time = null) {
        if ($time instanceof DateTimeImmutable) {
            $this->milliseconds = (int)($time->getTimestamp() * 1000);
        } elseif (is_numeric($time)) {
            $this->milliseconds = (int)$time;
        } else {
            $this->milliseconds = (int)(microtime(true) * 1000);
        }
    }

    public function toDateTime(): \DateTime {
        $dt = new \DateTime();
        $dt->setTimestamp((int)floor($this->milliseconds / 1000));
        return $dt;
    }

    public function getTimestamp(): int {
        return (int)floor($this->milliseconds / 1000);
    }

    public function __toString(): string {
        return (string)$this->milliseconds;
    }
}

class InsertResult {
    private string $id;
    public function __construct(string $id) { $this->id = $id; }
    public function getInsertedId(): string { return $this->id; }
}

class UpdateResult {
    private int $matched;
    private int $modified;
    public function __construct(int $matched, int $modified) {
        $this->matched = $matched;
        $this->modified = $modified;
    }
    public function getMatchedCount(): int { return $this->matched; }
    public function getModifiedCount(): int { return $this->modified; }
}

class DeleteResult {
    private int $deleted;
    public function __construct(int $deleted) { $this->deleted = $deleted; }
    public function getDeletedCount(): int { return $this->deleted; }
}

class Collection {
    private string $name;
    private LocalDatabase $db;

    public function __construct(string $name, LocalDatabase $db) {
        $this->name = $name;
        $this->db = $db;
    }

    public function findOne(array $filter = [], array $options = []): ?array {
        $data = $this->db->getCollectionData($this->name);
        foreach ($data as $item) {
            if ($this->matchesFilter($item, $filter)) {
                return $this->hydrateItem($item);
            }
        }
        return null;
    }

    public function find(array $filter = [], array $options = []): array {
        $data = $this->db->getCollectionData($this->name);
        $results = [];

        foreach ($data as $item) {
            if ($this->matchesFilter($item, $filter)) {
                $results[] = $this->hydrateItem($item);
            }
        }

        // Sorting
        if (!empty($options['sort']) && is_array($options['sort'])) {
            usort($results, function ($a, $b) use ($options) {
                foreach ($options['sort'] as $key => $direction) {
                    $valA = $a[$key] ?? null;
                    $valB = $b[$key] ?? null;

                    if ($valA instanceof MockUTCDateTime) $valA = $valA->getTimestamp();
                    if ($valB instanceof MockUTCDateTime) $valB = $valB->getTimestamp();

                    if ($valA == $valB) continue;

                    if ($direction === -1 || $direction === '-1' || strtolower((string)$direction) === 'desc') {
                        return ($valA < $valB) ? 1 : -1;
                    } else {
                        return ($valA > $valB) ? 1 : -1;
                    }
                }
                return 0;
            });
        }

        if (isset($options['limit']) && is_numeric($options['limit'])) {
            $results = array_slice($results, 0, (int)$options['limit']);
        }

        return $results;
    }

    public function insertOne(array $document): InsertResult {
        $data = $this->db->getCollectionData($this->name);
        $id = isset($document['_id']) ? (string)$document['_id'] : bin2hex(random_bytes(12));
        $document['_id'] = $id;

        $serialized = $this->dehydrateItem($document);
        $data[] = $serialized;
        $this->db->saveCollectionData($this->name, $data);

        return new InsertResult($id);
    }

    public function updateOne(array $filter, array $update): UpdateResult {
        $data = $this->db->getCollectionData($this->name);
        $matched = 0;
        $modified = 0;

        foreach ($data as $index => $item) {
            $hydrated = $this->hydrateItem($item);
            if ($this->matchesFilter($hydrated, $filter)) {
                $matched++;
                if (!empty($update['$set']) && is_array($update['$set'])) {
                    foreach ($update['$set'] as $k => $v) {
                        $hydrated[$k] = $v;
                    }
                    $data[$index] = $this->dehydrateItem($hydrated);
                    $modified++;
                }
                break; // updateOne updates the first match
            }
        }

        if ($modified > 0) {
            $this->db->saveCollectionData($this->name, $data);
        }

        return new UpdateResult($matched, $modified);
    }

    public function deleteOne(array $filter): DeleteResult {
        $data = $this->db->getCollectionData($this->name);
        $deleted = 0;

        foreach ($data as $index => $item) {
            $hydrated = $this->hydrateItem($item);
            if ($this->matchesFilter($hydrated, $filter)) {
                array_splice($data, $index, 1);
                $deleted++;
                break;
            }
        }

        if ($deleted > 0) {
            $this->db->saveCollectionData($this->name, $data);
        }

        return new DeleteResult($deleted);
    }

    public function countDocuments(array $filter = []): int {
        if (empty($filter)) {
            return count($this->db->getCollectionData($this->name));
        }
        $count = 0;
        $data = $this->db->getCollectionData($this->name);
        foreach ($data as $item) {
            if ($this->matchesFilter($item, $filter)) {
                $count++;
            }
        }
        return $count;
    }

    private function matchesFilter(array $item, array $filter): bool {
        foreach ($filter as $key => $expected) {
            $actual = $item[$key] ?? null;

            if ($key === '_id') {
                $actualStr = (string)$actual;
                if (is_array($expected)) {
                    if (isset($expected['$ne'])) {
                        if ($actualStr === (string)$expected['$ne']) return false;
                    }
                    if (isset($expected['$in'])) {
                        $inList = array_map('strval', $expected['$in']);
                        if (!in_array($actualStr, $inList, true)) return false;
                    }
                } else {
                    if ($actualStr !== (string)$expected) return false;
                }
                continue;
            }

            if (is_array($expected)) {
                // Operators
                if (isset($expected['$ne'])) {
                    if ($actual == $expected['$ne']) return false;
                }
                if (isset($expected['$in'])) {
                    $matchIn = false;
                    $actualVal = ($actual instanceof MockObjectId) ? (string)$actual : $actual;
                    foreach ($expected['$in'] as $target) {
                        $targetVal = ($target instanceof MockObjectId) ? (string)$target : $target;
                        if ($actualVal == $targetVal) {
                            $matchIn = true;
                            break;
                        }
                    }
                    if (!$matchIn) return false;
                }
                if (isset($expected['$regex'])) {
                    $pattern = '/' . $expected['$regex'] . '/' . ($expected['$options'] ?? '');
                    if (!preg_match($pattern, (string)$actual)) return false;
                }
                // Date comparisons ($lte, $gte)
                if (isset($expected['$lte'])) {
                    $actualTs = $this->toTimestamp($actual);
                    $targetTs = $this->toTimestamp($expected['$lte']);
                    if ($actualTs > $targetTs) return false;
                }
                if (isset($expected['$gte'])) {
                    $actualTs = $this->toTimestamp($actual);
                    $targetTs = $this->toTimestamp($expected['$gte']);
                    if ($actualTs < $targetTs) return false;
                }
            } else {
                $actualCompare = ($actual instanceof MockObjectId) ? (string)$actual : $actual;
                $expectedCompare = (is_object($expected) && method_exists($expected, '__toString')) ? (string)$expected : $expected;
                if ($actualCompare != $expectedCompare) {
                    return false;
                }
            }
        }
        return true;
    }

    private function toTimestamp($val): int {
        if ($val instanceof MockUTCDateTime) {
            return $val->getTimestamp();
        }
        if ($val instanceof UTCDateTime) {
            return $val->toDateTime()->getTimestamp();
        }
        if (is_object($val) && method_exists($val, 'toDateTime')) {
            return $val->toDateTime()->getTimestamp();
        }
        if (is_array($val) && isset($val['$date'])) {
            return (int)floor($val['$date'] / 1000);
        }
        if (is_numeric($val)) {
            return (int)$val;
        }
        if (is_string($val)) {
            $ts = strtotime($val);
            if ($ts !== false) return $ts;
        }
        return 0;
    }

    private function hydrateItem(array $raw): array {
        $hydrated = $raw;
        $hydrated['_id'] = new MockObjectId((string)$raw['_id']);

        if (isset($hydrated['userId'])) {
            $hydrated['userId'] = new MockObjectId((string)$raw['userId']);
        }
        if (isset($hydrated['vehicleId'])) {
            $hydrated['vehicleId'] = new MockObjectId((string)$raw['vehicleId']);
        }

        foreach (['startDate', 'endDate', 'createdAt', 'updatedAt', 'paidAt', 'pickedUpAt', 'returnedAt', 'completedAt'] as $dtKey) {
            if (isset($hydrated[$dtKey])) {
                if (is_array($hydrated[$dtKey]) && isset($hydrated[$dtKey]['$date'])) {
                    $hydrated[$dtKey] = new MockUTCDateTime((int)$hydrated[$dtKey]['$date']);
                } elseif (is_numeric($hydrated[$dtKey])) {
                    $hydrated[$dtKey] = new MockUTCDateTime((int)$hydrated[$dtKey]);
                } elseif ($hydrated[$dtKey] instanceof UTCDateTime) {
                    $hydrated[$dtKey] = new MockUTCDateTime($hydrated[$dtKey]->toDateTime());
                }
            }
        }

        return $hydrated;
    }

    private function dehydrateItem(array $item): array {
        $dehydrated = $item;
        $dehydrated['_id'] = (string)$item['_id'];

        if (isset($dehydrated['userId'])) {
            $dehydrated['userId'] = (string)$item['userId'];
        }
        if (isset($dehydrated['vehicleId'])) {
            $dehydrated['vehicleId'] = (string)$item['vehicleId'];
        }

        foreach (['startDate', 'endDate', 'createdAt', 'updatedAt', 'paidAt', 'pickedUpAt', 'returnedAt', 'completedAt'] as $dtKey) {
            if (isset($dehydrated[$dtKey])) {
                $val = $dehydrated[$dtKey];
                if ($val instanceof MockUTCDateTime) {
                    $dehydrated[$dtKey] = ['$date' => $val->getTimestamp() * 1000];
                } elseif ($val instanceof UTCDateTime) {
                    $dehydrated[$dtKey] = ['$date' => $val->toDateTime()->getTimestamp() * 1000];
                } elseif (is_object($val) && method_exists($val, 'toDateTime')) {
                    $dehydrated[$dtKey] = ['$date' => $val->toDateTime()->getTimestamp() * 1000];
                } elseif ($val instanceof \DateTimeInterface) {
                    $dehydrated[$dtKey] = ['$date' => $val->getTimestamp() * 1000];
                }
            }
        }

        return $dehydrated;
    }
}

class LocalDatabase {
    private string $storageFile;
    private array $data = [];

    public function __construct(string $storageFile) {
        $this->storageFile = $storageFile;
        $this->load();
    }

    public function selectCollection(string $name): Collection {
        return new Collection($name, $this);
    }

    public function __get(string $name): Collection {
        return $this->selectCollection($name);
    }

    public function getDatabaseName(): string {
        return 'vehicle_rental_system (Local Resilient Engine)';
    }

    public function listCollectionNames(): array {
        return array_keys($this->data);
    }

    public function getCollectionData(string $collection): array {
        return $this->data[$collection] ?? [];
    }

    public function saveCollectionData(string $collection, array $data): void {
        $this->data[$collection] = array_values($data);
        $this->save();
    }

    private function load(): void {
        if (!is_file($this->storageFile)) {
            $this->seedInitialData();
            return;
        }

        $content = @file_get_contents($this->storageFile);
        $decoded = $content ? json_decode($content, true) : null;
        if (!is_array($decoded)) {
            $this->seedInitialData();
        } else {
            $this->data = $decoded;
        }
    }

    private function save(): void {
        $dir = dirname($this->storageFile);
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }
        file_put_contents($this->storageFile, json_encode($this->data, JSON_PRETTY_PRINT), LOCK_EX);
    }

    public function seedInitialData(): void {
        $nowMs = (int)(microtime(true) * 1000);
        $adminHash = password_hash('Admin@123', PASSWORD_BCRYPT);
        $customerHash = password_hash('Customer@123', PASSWORD_BCRYPT);

        $adminId = '6520a0000000000000000001';
        $customerId = '6520a0000000000000000002';

        $users = [
            [
                '_id' => $adminId,
                'name' => 'System Administrator',
                'email' => 'admin@vstravells.com',
                'phone' => '9876543210',
                'password' => $adminHash,
                'role' => 'admin',
                'license' => 'DL-ADMIN-001',
                'address' => 'VS Travells HQ, Tech Park Hub, Bengaluru',
                'createdAt' => ['$date' => $nowMs],
                'updatedAt' => ['$date' => $nowMs]
            ],
            [
                '_id' => $customerId,
                'name' => 'Bhanu Customer',
                'email' => 'customer@vstravells.com',
                'phone' => '9123456780',
                'password' => $customerHash,
                'role' => 'user',
                'license' => 'DL-04-2022-8921',
                'address' => 'Flat 402, Green Meadows, Indiranagar',
                'createdAt' => ['$date' => $nowMs],
                'updatedAt' => ['$date' => $nowMs]
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
                'createdAt' => ['$date' => $nowMs],
                'updatedAt' => ['$date' => $nowMs]
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
                'createdAt' => ['$date' => $nowMs],
                'updatedAt' => ['$date' => $nowMs]
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
                'createdAt' => ['$date' => $nowMs],
                'updatedAt' => ['$date' => $nowMs]
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
                'createdAt' => ['$date' => $nowMs],
                'updatedAt' => ['$date' => $nowMs]
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
                'createdAt' => ['$date' => $nowMs],
                'updatedAt' => ['$date' => $nowMs]
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
                'description' => 'Executive sedan offering legendary comfort, lane watch camera, premium leather cabin, and punchy i-VTEC engine.',
                'createdAt' => ['$date' => $nowMs],
                'updatedAt' => ['$date' => $nowMs]
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
                'createdAt' => ['$date' => $nowMs],
                'updatedAt' => ['$date' => $nowMs]
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
                'createdAt' => ['$date' => $nowMs],
                'updatedAt' => ['$date' => $nowMs]
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
                'description' => 'Track-bred supersport bike with upside-down front forks, variable valve actuation (VVA), traction control system, and sleek aerodynamic fairing.',
                'createdAt' => ['$date' => $nowMs],
                'updatedAt' => ['$date' => $nowMs]
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
                'createdAt' => ['$date' => $nowMs],
                'updatedAt' => ['$date' => $nowMs]
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
                'createdAt' => ['$date' => $nowMs],
                'updatedAt' => ['$date' => $nowMs]
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
                'description' => 'Ultimate luxury sports sedan. 258 bhp TwinPower Turbo engine, 50:50 weight distribution, Harman Kardon surround audio, and M aerodynamics.',
                'createdAt' => ['$date' => $nowMs],
                'updatedAt' => ['$date' => $nowMs]
            ]
        ];

        $today = new DateTimeImmutable('today');
        $startDemo = $today->modify('+2 days');
        $endDemo = $today->modify('+5 days');

        $bookings = [
            [
                '_id' => '6520c0000000000000000001',
                'userId' => $customerId,
                'vehicleId' => '6520b0000000000000000001',
                'vehicleName' => 'Maruti Suzuki Swift VXi',
                'vehicleType' => 'Car',
                'pricePerDay' => 1800,
                'startDate' => ['$date' => $startDemo->getTimestamp() * 1000],
                'endDate' => ['$date' => $endDemo->getTimestamp() * 1000],
                'pickupLocation' => 'Bengaluru Airport Terminal 1',
                'returnLocation' => 'Indiranagar Hub',
                'totalDays' => 4,
                'totalPrice' => 7200,
                'status' => 'Confirmed',
                'paymentStatus' => 'Paid (Demo)',
                'paymentMethod' => 'UPI',
                'paymentReference' => 'VST982187342',
                'createdAt' => ['$date' => $nowMs],
                'updatedAt' => ['$date' => $nowMs],
                'paidAt' => ['$date' => $nowMs]
            ]
        ];

        $this->data = [
            'users' => $users,
            'vehicles' => $vehicles,
            'bookings' => $bookings
        ];

        $this->save();
    }
}
