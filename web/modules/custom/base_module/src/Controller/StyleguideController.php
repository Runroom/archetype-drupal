<?php

declare(strict_types=1);

namespace Drupal\base_module\Controller;

use Drupal\Core\Controller\ControllerBase;

class StyleguideController extends ControllerBase
{
    public function show()
    {
        return [
            '#theme' => 'styleguide',
            '#title' => $this->t('Styleguide'),
        ];
    }
}
