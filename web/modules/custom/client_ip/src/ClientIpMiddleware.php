<?php

declare(strict_types=1);

namespace Drupal\client_ip;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ClientIpMiddleware implements HttpKernelInterface
{
    private const COOKIE_NAME = 'client_ip';

    private HttpKernelInterface $app;

    public function __construct(HttpKernelInterface $app)
    {
        $this->app = $app;
    }

    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        $response = $this->app->handle($request, $type, $catch);

        if (null === $request->cookies->get(self::COOKIE_NAME)) {
            $response->headers->setCookie(
                new Cookie(self::COOKIE_NAME, $request->getClientIp(), 0, '/', null, true, false)
            );
        }

        return $response;
    }
}
