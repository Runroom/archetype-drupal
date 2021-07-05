<?php

declare(strict_types=1);

namespace Drupal\cookies;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;
use Drupal\Core\StringTranslation\TranslatableMarkup;

class CookiesEntityListBuilder extends EntityListBuilder
{
    public function buildHeader(): array
    {
        return [
            'name' => new TranslatableMarkup('Name'),
        ] + parent::buildHeader();
    }

    public function buildRow(EntityInterface $entity): array
    {
        return [
            'name' => Link::createFromRoute($entity->label() ?? '', 'entity.cookies_entity.edit_form', ['cookies_entity' => $entity->id()]),
        ] + parent::buildRow($entity);
    }
}
