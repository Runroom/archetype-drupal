<?php

declare(strict_types=1);

namespace Drupal\contact_form\Entity;

use Drupal\views\EntityViewsData;

class LeadViewsData extends EntityViewsData
{
    public function getViewsData(): array
    {
        $data = parent::getViewsData();

        return $data;
    }
}
