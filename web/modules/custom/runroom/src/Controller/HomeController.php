<?php

namespace Drupal\runroom\Controller;

use Drupal\Core\Controller\ControllerBase;


class HomeController extends ControllerBase {

  public function show() {
    return [
      '#theme' => 'home_block',
      '#title' => $this->t('Runroom Drupal Archetype'),
      '#text' => $this->t('Do or do not, there is no try.')
    ];
  }

}
