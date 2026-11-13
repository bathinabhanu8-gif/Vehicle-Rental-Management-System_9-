<?php
/**
 * DAA Algorithm 4: Greedy Vehicle Allocation (Intelligent Matchmaker)
 * Design and Analysis of Algorithms (DAA)
 *
 * Greedy Algorithm Paradigm:
 *   Builds up a solution piece by piece, always choosing the next piece that offers
 *   the most immediate benefit (the "greedy choice").
 *
 * Problem Formulation:
 *   Given:
 *     - Customer Total Budget: B
 *     - Minimum Required Passengers/Seats: S_req
 *     - Rental Duration in Days: D
 *     - Preferred Vehicle Type: T (Car, Bike, or Any)
 *     - Preferred Fuel Type: F (Petrol, Diesel, EV, or Any)
 *     - Available Fleet: V = { v_1, v_2, ..., v_n }
 *
 * Selection Strategy (Greedy Choice Property):
 *   1. Feasibility Filter:
 *      A vehicle v is eligible iff:
 *        - v.status == 'Available'
 *        - (T == 'Any' OR v.type == T)
 *        - v.seats >= S_req
 *        - (v.pricePerDay * D) <= B
 *   2. Objective Function:
 *      Greedy Fitness Score G(v) =
 *        w_budget * (1 - TotalCost(v) / B)       // Budget conservation efficiency
 *      + w_seats  * (S_req / v.seats)             // Seat capacity utilization (minimizes unused capacity)
 *      + w_pref   * Bonus(v.fuel == F)            // Customer preference satisfaction
 *   3. Greedy Step:
 *      Select v* = argmax_{v in Eligible} G(v).
 *
 * Limitations:
 *   - The algorithm makes locally optimal choices based on the current snapshot of fleet
 *     and individual booking request.
 *   - It does not look ahead to future high-demand booking requests that might need
 *     larger or specialized vehicles later (competitive ratio limitation).
 *
 * Time Complexity:  O(n) where n = total number of vehicles in fleet
 * Space Complexity: O(1) auxiliary space (O(k) for eligible ranking list)
 */

namespace DAA;

class GreedyVehicleAllocation
{
    /**
     * Recommend the optimal vehicle for a customer based on greedy selection.
     */
    public static function allocate(
        array $fleet,
        float $totalBudget,
        int $requiredSeats,
        int $rentalDays,
        string $preferredType = 'Any',
        string $preferredFuel = 'Any'
    ): array {
        $startTime = microtime(true);
        $rentalDays = max(1, $rentalDays);
        $candidates = [];

        $wBudget = 0.45;
        $wSeats = 0.35;
        $wPref = 0.20;

        foreach ($fleet as $v) {
            $status = (string)($v['status'] ?? 'Available');
            if ($status !== 'Available') continue;

            $type = (string)($v['type'] ?? 'Car');
            if ($preferredType !== 'Any' && strcasecmp($type, $preferredType) !== 0) {
                continue;
            }

            $seats = (int)($v['seats'] ?? ($type === 'Bike' ? 2 : 5));
            if ($seats < $requiredSeats) {
                continue;
            }

            $pricePerDay = (float)($v['pricePerDay'] ?? 0);
            $totalCost = $pricePerDay * $rentalDays;
            if ($totalCost > $totalBudget) {
                continue; // Exceeds budget
            }

            // Calculate Greedy Components
            // 1. Budget efficiency: higher score for staying well within budget without being unrealistically low
            $budgetRatio = $totalBudget > 0 ? (1.0 - ($totalCost / $totalBudget)) : 0;

            // 2. Seat efficiency: S_req / seats. If vehicle has exactly S_req seats, ratio = 1.0 (perfect fit).
            $seatRatio = $seats > 0 ? ($requiredSeats / $seats) : 0;

            // 3. Fuel preference match
            $fuel = (string)($v['fuel'] ?? 'Petrol');
            $prefScore = ($preferredFuel === 'Any' || strcasecmp($fuel, $preferredFuel) === 0) ? 1.0 : 0.0;

            // Composite Greedy Fitness Score
            $score = ($wBudget * $budgetRatio) + ($wSeats * $seatRatio) + ($wPref * $prefScore);

            $candidates[] = [
                'vehicle' => $v,
                'totalCost' => $totalCost,
                'costSavings' => round($totalBudget - $totalCost, 2),
                'budgetRatio' => round($budgetRatio, 3),
                'seatUtilization' => round($seatRatio * 100, 1) . '%',
                'greedyScore' => round($score, 4),
                'breakdown' => [
                    'budgetComponent' => round($wBudget * $budgetRatio, 4),
                    'seatComponent' => round($wSeats * $seatRatio, 4),
                    'preferenceComponent' => round($wPref * $prefScore, 4)
                ]
            ];
        }

        // Greedy Selection: pick candidate with maximum greedyScore
        usort($candidates, fn($a, $b) => $b['greedyScore'] <=> $a['greedyScore']);

        $bestChoice = !empty($candidates) ? $candidates[0] : null;
        $executionTimeMs = round((microtime(true) - $startTime) * 1000, 4);

        return [
            'algorithm' => 'Greedy Vehicle Allocation',
            'paradigm' => 'Greedy Choice Property',
            'timeComplexity' => 'O(n)',
            'spaceComplexity' => 'O(1)',
            'inputCriteria' => [
                'budget' => $totalBudget,
                'seats' => $requiredSeats,
                'days' => $rentalDays,
                'type' => $preferredType,
                'fuel' => $preferredFuel
            ],
            'foundMatch' => $bestChoice !== null,
            'allocatedVehicle' => $bestChoice,
            'alternativeCandidates' => array_slice($candidates, 1, 3),
            'totalCandidatesEvaluated' => count($candidates),
            'totalFleetSize' => count($fleet),
            'executionTimeMs' => $executionTimeMs,
            'academicJustification' => 'Greedy choice selects the vehicle maximizing seat efficiency and cost conservation simultaneously without backtracking.'
        ];
    }
}
