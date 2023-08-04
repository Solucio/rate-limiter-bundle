<?php

/*
 * This file is part of the SolucioRateLimiterBundle.
 *
 * (c) Solucio <info@solucio.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Solucio\Bundle\RateLimiter\EventListener;

use Solucio\Bundle\RateLimiter\Attribute\RateLimit;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\RateLimiter\RateLimiterFactory;

class RateLimitAttributeListener implements EventSubscriberInterface
{
    /**
     * @var RateLimiterFactory[]
     */
    protected array $rateLimitServices = [];

    public function addLimiter(string $name, RateLimiterFactory $service): void
    {
        $this->rateLimitServices[$name] = $service;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER_ARGUMENTS => ['onKernelControllerArguments', 10],
        ];
    }

    public function getRateLimiterFactory($serviceName): bool|RateLimiterFactory
    {
        if (!str_starts_with($serviceName, 'limiter.')) {
            $serviceName = 'limiter.' . $serviceName;
        }

        if (!array_key_exists($serviceName, $this->rateLimitServices)) {
            return false;
        }

        return $this->rateLimitServices[$serviceName];
    }

    public function onKernelControllerArguments(ControllerArgumentsEvent $event): void
    {
        $request = $event->getRequest();

        if (!\is_array(
            $attributes = $request->attributes->get('_ratelimit') ?? $event->getAttributes()[RateLimit::class] ?? null
        )) {
            return;
        }

        $serviceName = null;

        /** @var RateLimit $rateLimit */
        foreach ($attributes as $rateLimit) {
            if ($rateLimit->name !== null) {
                $serviceName = $rateLimit->name;
            }
        }

        if (!$rateLimiterFactory = $this->getRateLimiterFactory($serviceName)) {
            return;
        }

        $limiter = $rateLimiterFactory->create($request->getClientIp());
        $limit = $limiter->consume();

        $event->stopPropagation();

        if (false === $limit->isAccepted()) {
            throw new TooManyRequestsHttpException();
        }
    }
}