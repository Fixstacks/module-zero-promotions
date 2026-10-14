<?php
/**
 * Copyright © 2026 Roman Pasichnyk
 * SPDX-License-Identifier: OSL-3.0 OR AFL-3.0
 *
 * Zero_Promotions: more actions for Magento's cart price rules: "every Nth
 * item, the cheapest, X% off" (3 for 2, second at half price) and a free
 * gift added to the cart while the rule applies. See README.md.
 */
use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(ComponentRegistrar::MODULE, 'Zero_Promotions', __DIR__);
