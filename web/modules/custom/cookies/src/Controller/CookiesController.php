<?php

declare(strict_types=1);

namespace Drupal\cookies\Controller;

use Drupal\cookies\Service\CookiesService;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class CookiesController extends ControllerBase
{
    public const COOKIES_CACHE_TAGS = ['page:cookies'];

    private CookiesService $service;

    public function __construct(CookiesService $service)
    {
        $this->service = $service;
    }

    public static function create(ContainerInterface $container): self
    {
        return new static(
            $container->get('cookies.service.cookies'),
        );
    }

    public function getTitle(): string
    {
        $cookiesPage = $this->service->getCookiesPage();

        if (null === $cookiesPage) {
            return '';
        }

        return $cookiesPage->getName();
    }

    public function configuration(): array
    {
        return [
            '#theme' => 'cookies_page',
            '#cookiesPage' => $this->service->getCookiesPage(),
            '#cookies' => $this->service->getCookies(),
            '#form' => $this->service->getForm(),
            '#cache' => [
                'tags' => self::COOKIES_CACHE_TAGS,
            ],
        ];
    }
}
