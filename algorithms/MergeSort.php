<?php
/**
 * DAA Algorithm 2: Merge Sort
 * Design and Analysis of Algorithms (DAA)
 *
 * Divide-and-Conquer Paradigm:
 *   1. Divide:  Divide the n-element sequence to be sorted into two subsequences of n/2 elements each.
 *   2. Conquer: Sort the two subsequences recursively using merge sort.
 *   3. Combine: Merge the two sorted subsequences to produce the sorted answer.
 *
 * Time Complexity:
 *   - Best Case:    O(n log n)
 *   - Average Case: O(n log n)
 *   - Worst Case:   O(n log n)
 * Space Complexity: O(n) auxiliary space for temporary merging buffers
 * Stability: Stable (maintains relative order of equal keys)
 */

namespace DAA;

class MergeSort
{
    private static int $comparisons = 0;
    private static int $recursiveCalls = 0;
    private static array $traceLog = [];

    /**
     * Sort an array of vehicles or items by a given field.
     *
     * @param array  $items Items to sort
     * @param string $key   Field key (e.g., 'pricePerDay', 'seats', 'name')
     * @param string $order 'ASC' or 'DESC'
     * @return array Sorted array, comparison stats, execution time, and step trace
     */
    public static function sort(array $items, string $key = 'pricePerDay', string $order = 'ASC'): array
    {
        $startTime = microtime(true);
        self::$comparisons = 0;
        self::$recursiveCalls = 0;
        self::$traceLog = [];

        $isAsc = strtoupper(trim($order)) !== 'DESC';
        $sorted = self::mergeSortRecursive(array_values($items), $key, $isAsc, 0);

        $executionTimeMs = round((microtime(true) - $startTime) * 1000, 4);

        return [
            'algorithm' => 'Merge Sort',
            'timeComplexity' => 'O(n log n)',
            'spaceComplexity' => 'O(n)',
            'key' => $key,
            'order' => $isAsc ? 'ASC' : 'DESC',
            'itemCount' => count($items),
            'comparisons' => self::$comparisons,
            'recursiveCalls' => self::$recursiveCalls,
            'sortedData' => $sorted,
            'traceSample' => array_slice(self::$traceLog, 0, 15),
            'executionTimeMs' => $executionTimeMs
        ];
    }

    private static function mergeSortRecursive(array $items, string $key, bool $isAsc, int $depth): array
    {
        self::$recursiveCalls++;
        $count = count($items);

        if ($count <= 1) {
            return $items;
        }

        $mid = (int)floor($count / 2);
        $left = array_slice($items, 0, $mid);
        $right = array_slice($items, $mid);

        $leftSorted = self::mergeSortRecursive($left, $key, $isAsc, $depth + 1);
        $rightSorted = self::mergeSortRecursive($right, $key, $isAsc, $depth + 1);

        $merged = self::merge($leftSorted, $rightSorted, $key, $isAsc);

        if (count(self::$traceLog) < 30) {
            self::$traceLog[] = [
                'depth' => $depth,
                'leftCount' => count($leftSorted),
                'rightCount' => count($rightSorted),
                'mergedCount' => count($merged)
            ];
        }

        return $merged;
    }

    private static function merge(array $left, array $right, string $key, bool $isAsc): array
    {
        $result = [];
        $i = 0;
        $j = 0;
        $nL = count($left);
        $nR = count($right);

        while ($i < $nL && $j < $nR) {
            self::$comparisons++;
            $valL = $left[$i][$key] ?? null;
            $valR = $right[$j][$key] ?? null;

            $cmp = 0;
            if (is_numeric($valL) && is_numeric($valR)) {
                $cmp = ($valL == $valR) ? 0 : (($valL < $valR) ? -1 : 1);
            } else {
                $cmp = strcmp(strtolower((string)$valL), strtolower((string)$valR));
            }

            $condition = $isAsc ? ($cmp <= 0) : ($cmp >= 0);

            if ($condition) {
                $result[] = $left[$i];
                $i++;
            } else {
                $result[] = $right[$j];
                $j++;
            }
        }

        while ($i < $nL) {
            $result[] = $left[$i];
            $i++;
        }

        while ($j < $nR) {
            $result[] = $right[$j];
            $j++;
        }

        return $result;
    }
}
