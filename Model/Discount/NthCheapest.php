<?php
/**
 * Copyright © 2026 Roman Pasichnyk
 * SPDX-License-Identifier: OSL-3.0 OR AFL-3.0
 */

declare(strict_types=1);

namespace Zero\Promotions\Model\Discount;

use Magento\SalesRule\Model\Rule\Action\Discount\AbstractDiscount;
use Zero\Promotions\Model\Gifts;
use Zero\Promotions\Model\NthCheapest as Allocation;

/**
 * Action zero_nth_cheapest: of the cart's items the rule's actions match,
 * every Nth unit (N: Discount Qty Step), the cheapest ones, gets Discount
 * Amount % off; Maximum Qty Discount is Applied To limits how many units.
 */
class NthCheapest extends AbstractDiscount
{
    public function calculate($rule, $item, $qty)
    {
        $data = $this->discountFactory->create();
        $lines = [];
        $quote = $item->getQuote() ?? $item->getAddress()?->getQuote();
        if (!$quote) {
            return $data;
        }
        foreach ($quote->getAllVisibleItems() as $line) {
            if (Gifts::isGift($line) || !$rule->getActions()->validate($line)) {
                continue;
            }
            $lines[spl_object_id($line)] = ['price' => (float) $this->validator->getItemPrice($line), 'qty' => (float) $line->getTotalQty()];
        }
        $units = Allocation::allocate($lines, (int) $rule->getDiscountStep(), (int) $rule->getDiscountQty())[spl_object_id($item)] ?? 0;
        if (!$units) {
            return $data;
        }
        $pct = min(100.0, (float) $rule->getDiscountAmount()) / 100;
        $set = static fn (float $price, float $already) => round(min($units * $price * $pct, max(0.0, $qty * $price - $already)), 2);
        $data->setAmount($set((float) $this->validator->getItemPrice($item), (float) $item->getDiscountAmount()));
        $data->setBaseAmount($set((float) $this->validator->getItemBasePrice($item), (float) $item->getBaseDiscountAmount()));
        $data->setOriginalAmount($set((float) $this->validator->getItemOriginalPrice($item), (float) $item->getDiscountAmount()));
        $data->setBaseOriginalAmount($set((float) $this->validator->getItemBaseOriginalPrice($item), (float) $item->getBaseDiscountAmount()));

        return $data;
    }
}
