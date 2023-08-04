<?php

/*
 * This file is part of the SolucioRateLimiterBundle.
 *
 * (c) Solucio <info@solucio.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Solucio\Bundle\RateLimiter\DependencyInjection;

use MemberPoint\MP\CommonBundle\Messenger\AbstractMPCommonMessageHandler;
use Solucio\Bundle\RateLimiter\EventListener\RateLimitAttributeListener;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Throwable;

class RateLimitCompilePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $rateLimitAttributeService = $container->findDefinition('rate_limiter_attribute_listener');

        foreach ($container->getDefinitions() as $name => $definition) {
            if (str_starts_with($name, 'limiter')) {
                if ($definition instanceof ChildDefinition) {
                    $rateLimitAttributeService->addMethodCall('addLimiter', [$name, new Reference($name)]);
                }
            }
        }
    }
}
