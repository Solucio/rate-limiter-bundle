<?php

/*
 * This file is part of the SolucioRateLimiterBundle.
 *
 * (c) Solucio <info@solucio.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Solucio\Bundle\RateLimiter\EventListener\RateLimitAttributeListener;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\RateLimiter\RateLimiterFactory;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set('rate_limiter_attribute_listener', RateLimitAttributeListener::class)
        ->tag('kernel.event_subscriber');
};