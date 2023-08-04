<?php

/*
 * This file is part of the SolucioRateLimiterBundle.
 *
 * (c) Solucio <info@solucio.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Solucio\Bundle\RateLimiter\Attribute;

use Attribute;

#[Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class RateLimit
{
    public function __construct(
        public ?string $name = null
    )
    {
    }
}