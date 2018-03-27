<?php

namespace Drupal\runroom\Controller;

use Drupal\Core\Controller\ControllerBase;


class HomeController extends ControllerBase {

  public function show() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t("don't hassel the hoff!"),
    ];
  }

}
