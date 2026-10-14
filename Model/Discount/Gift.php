<?php
/**
 * Copyright © 2026 Roman Pasichnyk
 * SPDX-License-Identifier: OSL-3.0 OR AFL-3.0
 */

declare(strict_types=1);

namespace Zero\Promotions\Model\Discount;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\SalesRule\Model\Rule\Action\Discount\AbstractDiscount;
use Magento\SalesRule\Model\Rule\Action\Discount\DataFactory;
use Magento\SalesRule\Model\Validator;
use Zero\Promotions\Model\Gifts;

/**
 * Action zero_gift: no discount on the cart's items; that the rule applies
 * is noted, and after the totals the rule's gifts are in the cart, free.
 */
class Gift extends AbstractDiscount
{
    public function __construct(
        Validator $validator,
        DataFactory $discountDataFactory,
        PriceCurrencyInterface $priceCurrency,
        private readonly Gifts $gifts
    ) {
        parent::__construct($validator, $discountDataFactory, $priceCurrency);
    }

    public function calculate($rule, $item, $qty)
    {
        $quote = $item->getQuote() ?? $item->getAddress()?->getQuote();
        if ($quote && !Gifts::isGift($item)) {
            $this->gifts->applies($quote, $rule);
        }

        return $this->discountFactory->create();
    }
}
