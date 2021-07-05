<?php

declare(strict_types=1);

namespace Drupal\cookies\Repository;

use Drupal\cookies\Entity\CookiesEntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

class CookiesEntityRepository
{
    private EntityTypeManagerInterface $entityTypeManager;

    public function __construct(EntityTypeManagerInterface $entityTypeManager)
    {
        $this->entityTypeManager = $entityTypeManager;
    }

    public function getCookiesPage(): ?CookiesEntityInterface
    {
        $storage = $this->entityTypeManager->getStorage('cookies_entity');

        $cookiesEntity = $storage->load(1);
        \assert($cookiesEntity instanceof CookiesEntityInterface || null === $cookiesEntity);

        return $cookiesEntity;
    }
}
