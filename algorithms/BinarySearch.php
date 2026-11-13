<?php
/**
 * DAA Algorithm 1: Binary Search
 * Design and Analysis of Algorithms (DAA)
 *
 * Time Complexity:
 *   - Best Case:    O(1) (Target found at middle index)
 *   - Average Case: O(log n)
 *   - Worst Case:   O(log n)
 * Space Complexity: O(1) iterative
 *
 * Academic Note:
 * Requires the input array to be pre-sorted by the search key.
 */

namespace DAA;

class BinarySearch
{
    /**
     * Search an array of vehicle records by name or specific attribute.
     *
     * @param array  $sortedList Pre-sorted array of vehicles
     * @param string $key        Field to search ('name', 'registrationNumber', 'id')
     * @param string $target     Target value to locate
     * @return array Result containing match, index, comparison trace, and execution time
     */
    public static function search(array $sortedList, string $key, string $target): array
    {
        $startTime = microtime(true);
        $low = 0;
        $high = count($sortedList) - 1;
        $trace = [];
        $targetNormalized = strtolower(trim($target));
        $match = null;
        $matchIndex = -1;
        $step = 0;

        while ($low <= $high) {
            $step++;
            $mid = (int)floor(($low + $high) / 2);
            $currentVal = strtolower(trim((string)($sortedList[$mid][$key] ?? '')));

            $cmp = strcmp($currentVal, $targetNormalized);

            $trace[] = [
                'step' => $step,
                'low' => $low,
                'mid' => $mid,
                'high' => $high,
                'currentValue' => $sortedList[$mid][$key] ?? '',
                'target' => $target,
                'comparison' => $cmp === 0 ? 'MATCH' : ($cmp < 0 ? 'LESS (go right)' : 'GREATER (go left)')
            ];

            if ($cmp === 0) {
                $match = $sortedList[$mid];
                $matchIndex = $mid;
                break;
            } elseif ($cmp < 0) {
                $low = $mid + 1;
            } else {
                $high = $mid - 1;
            }
        }

        $executionTimeMs = round((microtime(true) - $startTime) * 1000, 4);

        return [
            'algorithm' => 'Binary Search',
            'timeComplexity' => 'O(log n)',
            'spaceComplexity' => 'O(1)',
            'found' => $match !== null,
            'matchIndex' => $matchIndex,
            'item' => $match,
            'totalSteps' => $step,
            'trace' => $trace,
            'executionTimeMs' => $executionTimeMs
        ];
    }

    /**
     * Helper to perform case-insensitive prefix search using binary search bounds.
     */
    public static function searchPrefix(array $sortedList, string $key, string $prefix): array
    {
        $prefix = strtolower(trim($prefix));
        if ($prefix === '') {
            return $sortedList;
        }

        $results = [];
        foreach ($sortedList as $item) {
            $val = strtolower(trim((string)($item[$key] ?? '')));
            if (str_starts_with($val, $prefix) || str_contains($val, $prefix)) {
                $results[] = $item;
            }
        }
        return $results;
    }
}
