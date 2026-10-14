<?php
/**
 * Copyright © 2026 Roman Pasichnyk
 * SPDX-License-Identifier: OSL-3.0 OR AFL-3.0
 */

declare(strict_types=1);

namespace Zero\Promotions\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;
use Zero\Promotions\Model\Gifts;

/**
 * After the totals: the gift lines follow the rules that apply; if they
 * changed, the totals are collected once more (the caller saves the cart).
 */
class SyncGifts implements ObserverInterface
{
    private bool $syncing = false;

    public function __construct(
        private readonly Gifts $gifts,
        private readonly LoggerInterface $logger
    ) {
    }

    public function execute(Observer $observer): void
    {
        if ($this->syncing) {
            return;
        }
        $quote = $observer->getEvent()->getQuote();
        $this->syncing = true;
        try {
            if ($this->gifts->sync($quote)) {
                $quote->setTotalsCollectedFlag(false);
                $quote->collectTotals();
            }
        } catch (\Throwable $e) {
            $this->logger->error('Zero_Promotions: gifts: ' . $e->getMessage());
        } finally {
            $this->syncing = false;
        }
    }
}
