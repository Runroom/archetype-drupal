<?php

declare(strict_types=1);

namespace Drupal\contact_form\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;

interface LeadInterface extends ContentEntityInterface, EntityChangedInterface
{
    public function getName(): string;

    public function setName(string $name): self;

    public function getCreatedTime(): int;

    public function setCreatedTime(int $timestamp): self;
}
