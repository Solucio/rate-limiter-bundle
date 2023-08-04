<?php

/*
 * This file is part of the SolucioRateLimiterBundle.
 *
 * (c) Solucio <info@solucio.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Solucio\Bundle\RateLimiter;

use Solucio\Bundle\RateLimiter\DependencyInjection\RateLimitCompilePass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\RateLimiter\RateLimiterFactory;

class RateLimiterBundle extends AbstractBundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RateLimitCompilePass(), PassConfig::TYPE_OPTIMIZE, 100);
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import(realpath($this->getPath() . '/config/services.php'));
    }
}