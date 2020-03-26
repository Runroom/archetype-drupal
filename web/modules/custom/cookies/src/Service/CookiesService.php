<?php

namespace Drupal\cookies\Service;

use Drupal\cookies\Entity\CookiesEntity;
use Drupal\cookies\Form\CookiesForm;
use Drupal\cookies\Repository\CookiesEntityRepository;
use Drupal\language\ConfigurableLanguageManager;

class CookiesService
{
    protected $cookies;
    protected $languageManager;
    protected $repository;

    public function __construct(
        array $cookies,
        ConfigurableLanguageManager $languageManager,
        CookiesEntityRepository $repository
    ) {
        $this->cookies = $cookies;
        $this->languageManager = $languageManager;
        $this->repository = $repository;
    }

    public function getCookiesPage(): ?CookiesEntity
    {
        $cookies = $this->repository->getCookiesPage();
        $locale = $this->getCurrentLocale();

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
        return \Drupal::formBuilder()->getForm(CookiesForm::class);
    }

    public function getCookiesByType(string $type): ?array
    {
        $cookies = [];
        foreach ($this->cookies[$type] as $category) {
            $cookies = \array_merge($cookies, $category['cookies'] ?? []);
        }

        return $cookies;
    }

    private function getCurrentLocale(): string
    {
        return $this->languageManager->getCurrentLanguage()->getId();
    }
}
