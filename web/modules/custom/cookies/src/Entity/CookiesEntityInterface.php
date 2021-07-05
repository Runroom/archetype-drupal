<?php

declare(strict_types=1);

namespace Drupal\cookies\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;

interface CookiesEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface
{
    public function getName(): string;

    public function setName(string $name): self;

    public function getCreatedTime(): int;

    public function setCreatedTime(int $timestamp): self;
}
