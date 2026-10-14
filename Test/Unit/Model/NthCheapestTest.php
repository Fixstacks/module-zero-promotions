<?php
/**
 * Copyright © 2026 Roman Pasichnyk
 * SPDX-License-Identifier: OSL-3.0 OR AFL-3.0
 */

declare(strict_types=1);

namespace Zero\Promotions\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use Zero\Promotions\Model\NthCheapest;

class NthCheapestTest extends TestCase
{
    public function testThreeForTwoTakesTheCheapest(): void
    {
        $this->assertSame(['b' => 1], NthCheapest::allocate([
            'a' => ['price' => 30.0, 'qty' => 1.0],
            'b' => ['price' => 10.0, 'qty' => 1.0],
            'c' => ['price' => 20.0, 'qty' => 1.0],
        ], 3));
    }

    public function testUnitsOfOneLineCountApart(): void
    {
        // 4 shirts at 10 and 1 at 50, every 2nd: the 2 cheapest units, both shirts.
        $this->assertSame(['shirt' => 2], NthCheapest::allocate([
            'shirt' => ['price' => 10.0, 'qty' => 4.0],
            'jacket' => ['price' => 50.0, 'qty' => 1.0],
        ], 2));
    }

    public function testTooFewItemsAndTheLimit(): void
    {
        $this->assertSame([], NthCheapest::allocate(['a' => ['price' => 5.0, 'qty' => 2.0]], 3));
        $this->assertSame(['a' => 1], NthCheapest::allocate(['a' => ['price' => 5.0, 'qty' => 6.0]], 2, 1));
        $this->assertSame([], NthCheapest::allocate(['a' => ['price' => 5.0, 'qty' => 6.0]], 1));
    }
}
