<?php

declare(strict_types=1);

namespace Drupal\contact_form;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * @ingroup contact_form
 */
class LeadListBuilder extends EntityListBuilder
{
    public function buildHeader(): array
    {
        $header['name'] = $this->t('contact_form.name');

        return $header + parent::buildHeader();
    }

    public function buildRow(EntityInterface $entity): array
    {
        $row['name'] = Link::createFromRoute(
            $entity->label(),
            'entity.lead.edit_form',
            ['lead' => $entity->id()]
        );

        return $row + parent::buildRow($entity);
    }
}
