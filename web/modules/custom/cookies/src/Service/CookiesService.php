<?php

declare(strict_types=1);

namespace Drupal\cookies\Service;

use Drupal\cookies\Entity\CookiesEntityInterface;
use Drupal\cookies\Form\CookiesForm;
use Drupal\cookies\Repository\CookiesEntityRepository;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\language\ConfigurableLanguageManager;

class CookiesService
{
    private array $cookies;
    private ConfigurableLanguageManager $languageManager;
    private CookiesEntityRepository $repository;
    private FormBuilderInterface $formBuilder;

    public function __construct(
        array $cookies,
        ConfigurableLanguageManager $languageManager,
        CookiesEntityRepository $repository,
        FormBuilderInterface $formBuilder
    ) {
        $this->cookies = $cookies;
        $this->languageManager = $languageManager;
        $this->repository = $repository;
        $this->formBuilder = $formBuilder;
    }

    public function getCookiesPage(): ?CookiesEntityInterface
    {
        $cookies = $this->repository->getCookiesPage();

        if (null === $cookies) {
            return null;
        }

        $locale = $this->languageManager->getCurrentLanguage()->getId();

        if ($cookies->hasTranslation($locale)) {
            return $cookies->getTranslation($locale);
        }

        return $cookies;
    }

    public function getCookies(): array
    {
        return $this->cookies;
    }

    public function getForm(): array
    {
        return $this->formBuilder->getForm(CookiesForm::class);
    }

    public function getCookiesByType(string $type): ?array
    {
        $cookies = [];
        foreach ($this->cookies[$type] as $category) {
            $cookies = array_merge($cookies, $category['cookies'] ?? []);
        }

        return $cookies;
    }
}
