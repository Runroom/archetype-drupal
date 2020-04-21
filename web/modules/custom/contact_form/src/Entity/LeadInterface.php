<?php

namespace Drupal\contact_form\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * @ingroup contact_form
 */
interface LeadInterface extends ContentEntityInterface, EntityChangedInterface
{
    public function getName(): ?string;

    public function setName(?string $name): self;

    public function getCreatedTime(): ?int;

    public function setCreatedTime(?int $timestamp): self;
}
