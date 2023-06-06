<?php

declare(strict_types=1);

namespace Drupal\client_ip;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

final class ClientIpMiddleware implements HttpKernelInterface
{
    private const COOKIE_NAME = 'client_ip';

    public function __construct(private readonly HttpKernelInterface $app)
    {
    }

    /**
     * @todo: Change the value for type for Drupal 10 to self::MAIN_REQUEST and add the type hints
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true): Response
    {
        $response = $this->app->handle($request, $type, $catch);

        if (null === $request->cookies->get(self::COOKIE_NAME)) {
            $response->headers->setCookie(
                Cookie::create(self::COOKIE_NAME, $request->getClientIp(), 0, '/', null, true, false)
            );
        }

        return $response;
    }
}
