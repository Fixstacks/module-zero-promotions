<?php
/**
 * Copyright © 2026 Roman Pasichnyk
 * SPDX-License-Identifier: OSL-3.0 OR AFL-3.0
 */

declare(strict_types=1);

namespace Zero\Promotions\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Zero\Promotions\Model\Gifts;

class ResetGifts implements ObserverInterface
{
    public function __construct(private readonly Gifts $gifts)
    {
    }

    public function execute(Observer $observer): void
    {
        $this->gifts->reset($observer->getEvent()->getQuote());
    }
}
