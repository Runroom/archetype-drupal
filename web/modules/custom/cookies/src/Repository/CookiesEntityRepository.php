<?php

declare(strict_types=1);

namespace Drupal\cookies\Repository;

use Drupal\cookies\Entity\CookiesEntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

final class CookiesEntityRepository
{
    public function __construct(private readonly EntityTypeManagerInterface $entityTypeManager)
    {
    }

    public function getCookiesPage(): ?CookiesEntityInterface
    {
        $storage = $this->entityTypeManager->getStorage('cookies_entity');

        $cookiesEntity = $storage->load(1);
        \assert($cookiesEntity instanceof CookiesEntityInterface || null === $cookiesEntity);

        return $cookiesEntity;
    }
}
