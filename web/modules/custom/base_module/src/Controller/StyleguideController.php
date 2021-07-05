<?php

declare(strict_types=1);

namespace Drupal\base_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;

class StyleguideController extends ControllerBase
{
    public function show(): array
    {
        return [
            '#theme' => 'styleguide',
            '#title' => new TranslatableMarkup('Styleguide'),
        ];
    }
}
