<?php

namespace Drupal\cookies\Repository;

use Drupal\cookies\Entity\CookiesEntity;
use Drupal\Core\Entity\EntityTypeManagerInterface;

class CookiesEntityRepository
{
    protected $entityTypeManager;

    public function __construct(EntityTypeManagerInterface $entityTypeManager)
    {
        $this->entityTypeManager = $entityTypeManager;
    }

    public function getCookiesPage(): ?CookiesEntity
    {
        return $this->entityTypeManager
            ->getStorage('cookies_entity')
            ->load(1);
    }
}
