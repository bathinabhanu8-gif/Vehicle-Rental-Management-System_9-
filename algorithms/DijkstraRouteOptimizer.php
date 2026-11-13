<?php
/**
 * DAA Algorithm 5: Dijkstra's Shortest Path Algorithm
 * Design and Analysis of Algorithms (DAA)
 *
 * Problem Statement:
 *   Given a weighted graph G = (V, E) representing the transit network of rental hubs,
 *   airports, and city centers with non-negative edge weights w(u, v) representing
 *   road distance in kilometers, find the shortest path from a source hub 's' to a
 *   destination hub 't'.
 *
 * Algorithm Working:
 *   1. Initialize distances dist[v] = infinity for all v in V, dist[s] = 0.
 *   2. Maintain a priority queue Q of vertices keyed by their current dist value.
 *   3. Extract minimum vertex u = argmin dist[v] from Q.
 *   4. For each neighbor v of u, relax edge (u, v):
 *      if dist[u] + w(u, v) < dist[v] then
 *         dist[v] = dist[u] + w(u, v)
 *         prev[v] = u
 *   5. Repeat until destination reached or Q is empty.
 *
 * Time Complexity:
 *   - With Min-Heap / Priority Queue: O((|V| + |E|) log |V|)
 *   - With Adjacency Matrix:          O(|V|^2)
 * Space Complexity:                   O(|V|) for distance and predecessor maps
 */

namespace DAA;

use SplPriorityQueue;

class DijkstraRouteOptimizer
{
    /**
     * Pre-defined network of city hubs and rental stations.
     */
    private static array $hubs = [
        'BLR_AIRPORT' => ['name' => 'Kempegowda International Airport Hub', 'lat' => 13.1986, 'lng' => 77.7066],
        'HEBBAL' => ['name' => 'Hebbal North Interchange Depot', 'lat' => 13.0358, 'lng' => 77.5970],
        'MG_ROAD' => ['name' => 'Central Business District (MG Road)', 'lat' => 12.9756, 'lng' => 77.6066],
        'INDIRANAGAR' => ['name' => 'Indiranagar 100ft Road Metro Hub', 'lat' => 12.9784, 'lng' => 77.6408],
        'KORAMANGALA' => ['name' => 'Koramangala 5th Block Station', 'lat' => 12.9352, 'lng' => 77.6245],
        'WHITEFIELD' => ['name' => 'Whitefield ITPL Tech Corridor Depot', 'lat' => 12.9863, 'lng' => 77.7337],
        'ELECTRONIC_CITY' => ['name' => 'Electronic City Tollgate Hub', 'lat' => 12.8399, 'lng' => 77.6770],
        'JAYANAGAR' => ['name' => 'Jayanagar 4th Block Terminal', 'lat' => 12.9250, 'lng' => 77.5838],
        'MYSORE_ROAD' => ['name' => 'Mysore Road Satellite Depot', 'lat' => 12.9510, 'lng' => 77.5350],
        'YESHWANTHPUR' => ['name' => 'Yeshwanthpur Railway Junction Hub', 'lat' => 13.0238, 'lng' => 77.5501]
    ];

    /**
     * Undirected weighted edges representing road distances (in km).
     */
    private static array $edges = [
        ['from' => 'BLR_AIRPORT', 'to' => 'HEBBAL', 'km' => 28.5],
        ['from' => 'BLR_AIRPORT', 'to' => 'WHITEFIELD', 'km' => 37.0],
        ['from' => 'HEBBAL', 'to' => 'YESHWANTHPUR', 'km' => 6.2],
        ['from' => 'HEBBAL', 'to' => 'MG_ROAD', 'km' => 9.4],
        ['from' => 'YESHWANTHPUR', 'to' => 'MYSORE_ROAD', 'km' => 11.0],
        ['from' => 'MG_ROAD', 'to' => 'INDIRANAGAR', 'km' => 4.5],
        ['from' => 'MG_ROAD', 'to' => 'JAYANAGAR', 'km' => 6.8],
        ['from' => 'INDIRANAGAR', 'to' => 'WHITEFIELD', 'km' => 14.2],
        ['from' => 'INDIRANAGAR', 'to' => 'KORAMANGALA', 'km' => 5.8],
        ['from' => 'KORAMANGALA', 'to' => 'ELECTRONIC_CITY', 'km' => 13.5],
        ['from' => 'KORAMANGALA', 'to' => 'JAYANAGAR', 'km' => 4.2],
        ['from' => 'JAYANAGAR', 'to' => 'MYSORE_ROAD', 'km' => 7.5],
        ['from' => 'ELECTRONIC_CITY', 'to' => 'WHITEFIELD', 'km' => 24.0]
    ];

    /**
     * Compute shortest path between two hubs using Dijkstra's Algorithm.
     */
    public static function computeShortestPath(string $source, string $destination): array
    {
        $startTime = microtime(true);
        $source = strtoupper(trim($source));
        $destination = strtoupper(trim($destination));

        if (!isset(self::$hubs[$source])) {
            $source = 'MG_ROAD';
        }
        if (!isset(self::$hubs[$destination])) {
            $destination = 'BLR_AIRPORT';
        }

        // Build Adjacency List
        $adj = [];
        foreach (self::$hubs as $hubKey => $meta) {
            $adj[$hubKey] = [];
        }
        foreach (self::$edges as $e) {
            $u = $e['from'];
            $v = $e['to'];
            $w = (float)$e['km'];
            $adj[$u][] = ['to' => $v, 'weight' => $w];
            $adj[$v][] = ['to' => $u, 'weight' => $w]; // Undirected road network
        }

        // Dijkstra data structures
        $dist = [];
        $prev = [];
        $visited = [];
        $trace = [];

        foreach (self::$hubs as $hubKey => $meta) {
            $dist[$hubKey] = INF;
            $prev[$hubKey] = null;
            $visited[$hubKey] = false;
        }

        $dist[$source] = 0.0;

        // SplPriorityQueue in PHP is a Max-Heap; to make it a Min-Heap, insert negative distance
        $pq = new SplPriorityQueue();
        $pq->setExtractFlags(SplPriorityQueue::EXTR_DATA);
        $pq->insert($source, 0.0);

        $step = 0;

        while (!$pq->isEmpty()) {
            $u = $pq->extract();

            if ($visited[$u]) continue;
            $visited[$u] = true;

            $step++;
            $trace[] = [
                'step' => $step,
                'currentHub' => $u,
                'currentDistanceKm' => round($dist[$u], 2),
                'name' => self::$hubs[$u]['name']
            ];

            if ($u === $destination) {
                break; // Found shortest path to destination
            }

            foreach ($adj[$u] as $edge) {
                $v = $edge['to'];
                $weight = $edge['weight'];

                if (!$visited[$v]) {
                    $newDist = $dist[$u] + $weight;
                    if ($newDist < $dist[$v]) {
                        $dist[$v] = $newDist;
                        $prev[$v] = $u;
                        $pq->insert($v, -$newDist); // Negative for Min-Heap behavior
                    }
                }
            }
        }

        // Reconstruct shortest path
        $path = [];
        $curr = $destination;
        if ($dist[$destination] !== INF) {
            while ($curr !== null) {
                array_unshift($path, [
                    'hubCode' => $curr,
                    'name' => self::$hubs[$curr]['name'],
                    'cumulativeKm' => round($dist[$curr], 2)
                ]);
                $curr = $prev[$curr];
            }
        }

        $totalKm = $dist[$destination] === INF ? 0 : round($dist[$destination], 2);
        // Estimated travel duration assuming average urban-highway speed of 45 km/h
        $estimatedMinutes = $totalKm > 0 ? (int)round(($totalKm / 45) * 60) : 0;
        // Transit surcharge: ₹10 per km for inter-hub vehicle transit
        $transitSurcharge = round($totalKm * 10, 2);

        $executionTimeMs = round((microtime(true) - $startTime) * 1000, 4);

        return [
            'algorithm' => "Dijkstra's Shortest Path Algorithm",
            'timeComplexity' => 'O((|V| + |E|) log |V|)',
            'spaceComplexity' => 'O(|V|)',
            'source' => [
                'code' => $source,
                'name' => self::$hubs[$source]['name']
            ],
            'destination' => [
                'code' => $destination,
                'name' => self::$hubs[$destination]['name']
            ],
            'pathFound' => $dist[$destination] !== INF,
            'totalDistanceKm' => $totalKm,
            'estimatedMinutes' => $estimatedMinutes,
            'transitSurchargeINR' => $transitSurcharge,
            'hopCount' => max(0, count($path) - 1),
            'path' => $path,
            'algorithmTrace' => $trace,
            'allHubs' => self::$hubs,
            'allEdges' => self::$edges,
            'executionTimeMs' => $executionTimeMs
        ];
    }

    public static function getHubs(): array
    {
        return self::$hubs;
    }
}
