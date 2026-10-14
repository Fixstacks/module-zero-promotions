<?php
/**
 * Copyright © 2026 Roman Pasichnyk
 * SPDX-License-Identifier: OSL-3.0 OR AFL-3.0
 */

declare(strict_types=1);

namespace Zero\Promotions\Model;

/**
 * "Every Nth item, the cheapest": of the cart's matching units (one per
 * piece: 3 of a shirt are 3 units), one in every N gets the discount, the
 * cheapest ones. 3 for 2: N = 3; second at half price: N = 2.
 */
class NthCheapest
{
    /**
     * @param array<string|int, array{price: float, qty: float}> $lines By line key
     * @param int $limit At most this many discounted units (0: no limit)
     * @return array<string|int, int> Discounted units by line key
     */
    public static function allocate(array $lines, int $step, int $limit = 0): array
    {
        if ($step < 2) {
            return [];
        }
        $units = [];
        foreach ($lines as $key => $line) {
            for ($i = 0; $i < (int) floor($line['qty']); $i++) {
                $units[] = [$key, (float) $line['price']];
            }
        }
        // Cheapest first; equal prices keep the cart's order.
        $order = array_keys($units);
        usort($order, static fn ($a, $b) => [$units[$a][1], $a] <=> [$units[$b][1], $b]);
        $count = intdiv(count($units), $step);
        if ($limit > 0) {
            $count = min($count, $limit);
        }
        $result = [];
        foreach (array_slice($order, 0, $count) as $index) {
            $key = $units[$index][0];
            $result[$key] = ($result[$key] ?? 0) + 1;
        }

        return $result;
    }
}
