<?php

namespace Drupal\runroom_module\Controller;

use Drupal\Core\Controller\ControllerBase;


class HomeController extends ControllerBase {

  public function show() {
    return [
      '#theme' => 'home_block',
      '#title' => $this->t('Runroom Drupal Archetype'),
      '#text' => $this->t('This is the Home of Runroom Drupal Archetype')
    ];
  }

}
