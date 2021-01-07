<?php

declare(strict_types=1);

namespace Drupal\cookies;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

class CookiesEntityListBuilder extends EntityListBuilder
{
    public function buildHeader()
    {
        $header['name'] = $this->t('Name');

        return $header + parent::buildHeader();
    }

    public function buildRow(EntityInterface $entity)
    {
        $row['name'] = Link::createFromRoute(
            $entity->label(),
            'entity.cookies_entity.edit_form',
            ['cookies_entity' => $entity->id()]
        );

        return $row + parent::buildRow($entity);
    }
}
