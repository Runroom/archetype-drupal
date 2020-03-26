<?php

namespace Drupal\cookies\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;

interface CookiesEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface
{
    public function getName();

    public function setName($name);

    public function getCreatedTime();

    public function setCreatedTime($timestamp);
}
