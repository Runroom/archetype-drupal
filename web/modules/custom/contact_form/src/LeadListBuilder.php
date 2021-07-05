<?php

declare(strict_types=1);

namespace Drupal\contact_form;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * @ingroup contact_form
 */
class LeadListBuilder extends EntityListBuilder
{
    public function buildHeader(): array
    {
        return [
            'name' => new TranslatableMarkup('contact_form.name'),
        ] + parent::buildHeader();
    }

    public function buildRow(EntityInterface $entity): array
    {
        return [
            'name' => Link::createFromRoute($entity->label() ?? '', 'entity.lead.edit_form', ['lead' => $entity->id()]),
        ] + parent::buildRow($entity);
    }
}
