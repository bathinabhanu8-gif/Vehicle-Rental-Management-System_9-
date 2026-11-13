<?php
/**
 * DAA Algorithm 3: Interval Overlap Detection
 * Design and Analysis of Algorithms (DAA)
 *
 * Problem Statement:
 *   Given an incoming booking request interval I_req = [s_req, e_req] and a set of
 *   k active scheduled reservations { I_1, I_2, ..., I_k }, determine if I_req
 *   conflicts with any existing reservation.
 *
 * Mathematical Theorem:
 *   Two closed intervals A = [s_A, e_A] and B = [s_B, e_B] intersect (overlap)
 *   if and only if:
 *     max(s_A, s_B) <= min(e_A, e_B)
 *   Equivalent Boolean formulation:
 *     (s_A <= e_B) AND (e_A >= s_B)
 *
 * Time Complexity:
 *   - Linear Scan against k intervals: O(k)
 *   - Sorted Interval Sweep / Interval Tree: O(k log k)
 * Space Complexity: O(1) auxiliary space (O(k) for storing conflict details)
 */

namespace DAA;

use DateTimeImmutable;

class IntervalOverlap
{
    /**
     * Check if a proposed interval overlaps with any active intervals.
     *
     * @param string|int|DateTimeImmutable $reqStart Proposed start date (Y-m-d or timestamp)
     * @param string|int|DateTimeImmutable $reqEnd   Proposed end date (Y-m-d or timestamp)
     * @param array $existingBookings List of existing bookings with 'startDate' and 'endDate'
     * @return array Conflict report with boolean hasConflict, conflicting bookings, and metrics
     */
    public static function checkOverlap($reqStart, $reqEnd, array $existingBookings): array
    {
        $startTime = microtime(true);
        $sReq = self::toTimestamp($reqStart);
        $eReq = self::toTimestamp($reqEnd);

        if ($eReq < $sReq) {
            return [
                'algorithm' => 'Interval Overlap Detection',
                'hasConflict' => true,
                'error' => 'Invalid date range: end date is before start date.',
                'conflicts' => [],
                'totalChecked' => 0,
                'executionTimeMs' => 0
            ];
        }

        $conflicts = [];
        $checked = 0;

        foreach ($existingBookings as $b) {
            // Ignore cancelled, rejected, or completed bookings
            $status = (string)($b['status'] ?? '');
            if (in_array($status, ['Cancelled', 'Rejected', 'Completed'], true)) {
                continue;
            }

            $checked++;
            $sExist = self::toTimestamp($b['startDate'] ?? 0);
            $eExist = self::toTimestamp($b['endDate'] ?? 0);

            // Interval intersection theorem: max(s1, s2) <= min(e1, e2)
            $overlapStart = max($sReq, $sExist);
            $overlapEnd = min($eReq, $eExist);

            if ($overlapStart <= $overlapEnd) {
                $conflicts[] = [
                    'bookingId' => (string)($b['id'] ?? $b['_id'] ?? 'unknown'),
                    'vehicleName' => $b['vehicleName'] ?? 'Vehicle',
                    'existingStart' => date('Y-m-d', $sExist),
                    'existingEnd' => date('Y-m-d', $eExist),
                    'status' => $status,
                    'overlapStartDate' => date('Y-m-d', $overlapStart),
                    'overlapEndDate' => date('Y-m-d', $overlapEnd),
                    'overlapDays' => (int)floor(($overlapEnd - $overlapStart) / 86400) + 1
                ];
            }
        }

        $executionTimeMs = round((microtime(true) - $startTime) * 1000, 4);

        return [
            'algorithm' => 'Interval Overlap Detection',
            'timeComplexity' => 'O(k)',
            'spaceComplexity' => 'O(1)',
            'requestedStart' => date('Y-m-d', $sReq),
            'requestedEnd' => date('Y-m-d', $eReq),
            'hasConflict' => count($conflicts) > 0,
            'conflictCount' => count($conflicts),
            'conflicts' => $conflicts,
            'totalIntervalsChecked' => $checked,
            'executionTimeMs' => $executionTimeMs
        ];
    }

    /**
     * Finds free available intervals within a target evaluation window.
     */
    public static function findAvailableSlots($windowStart, $windowEnd, array $existingBookings): array
    {
        $wStart = self::toTimestamp($windowStart);
        $wEnd = self::toTimestamp($windowEnd);

        // Collect and sort all active intervals
        $intervals = [];
        foreach ($existingBookings as $b) {
            $status = (string)($b['status'] ?? '');
            if (in_array($status, ['Cancelled', 'Rejected', 'Completed'], true)) continue;

            $s = max($wStart, self::toTimestamp($b['startDate']));
            $e = min($wEnd, self::toTimestamp($b['endDate']));
            if ($s <= $e) {
                $intervals[] = ['start' => $s, 'end' => $e];
            }
        }

        // Sort intervals by start timestamp (O(k log k))
        usort($intervals, fn($a, $b) => $a['start'] <=> $b['start']);

        // Merge overlapping busy intervals
        $merged = [];
        foreach ($intervals as $curr) {
            if (empty($merged)) {
                $merged[] = $curr;
            } else {
                $lastIdx = count($merged) - 1;
                if ($curr['start'] <= $merged[$lastIdx]['end'] + 86400) {
                    $merged[$lastIdx]['end'] = max($merged[$lastIdx]['end'], $curr['end']);
                } else {
                    $merged[] = $curr;
                }
            }
        }

        // Invert busy intervals to find free gaps
        $freeSlots = [];
        $cursor = $wStart;

        foreach ($merged as $busy) {
            if ($cursor < $busy['start']) {
                $gapEnd = $busy['start'] - 86400;
                $freeSlots[] = [
                    'startDate' => date('Y-m-d', $cursor),
                    'endDate' => date('Y-m-d', $gapEnd),
                    'days' => (int)floor(($gapEnd - $cursor) / 86400) + 1
                ];
            }
            $cursor = $busy['end'] + 86400;
        }

        if ($cursor <= $wEnd) {
            $freeSlots[] = [
                'startDate' => date('Y-m-d', $cursor),
                'endDate' => date('Y-m-d', $wEnd),
                'days' => (int)floor(($wEnd - $cursor) / 86400) + 1
            ];
        }

        return $freeSlots;
    }

    private static function toTimestamp($val): int
    {
        if (is_numeric($val)) return (int)$val;
        if ($val instanceof DateTimeImmutable || $val instanceof \DateTime) return $val->getTimestamp();
        if (is_object($val) && method_exists($val, 'toDateTime')) return $val->toDateTime()->getTimestamp();
        if (is_array($val) && isset($val['$date'])) return (int)floor($val['$date'] / 1000);
        $ts = strtotime((string)$val);
        return $ts !== false ? $ts : 0;
    }
}
