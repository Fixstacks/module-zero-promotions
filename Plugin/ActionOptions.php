<?php
/**
 * Copyright © 2026 Roman Pasichnyk
 * SPDX-License-Identifier: OSL-3.0 OR AFL-3.0
 */

declare(strict_types=1);

namespace Zero\Promotions\Plugin;

use Magento\SalesRule\Model\Rule\Action\SimpleActionOptionsProvider;

/**
 * Zero's actions in the cart price rule's Apply list.
 */
class ActionOptions
{
    public function afterToOptionArray(SimpleActionOptionsProvider $subject, array $options): array
    {
        $options[] = ['label' => __('Every Nth item, the cheapest: Discount Amount % off (N: Discount Qty Step)'), 'value' => 'zero_nth_cheapest'];
        $options[] = ['label' => __('Free gift: add the Gift SKUs to the cart for free'), 'value' => 'zero_gift'];

        return $options;
    }
}
