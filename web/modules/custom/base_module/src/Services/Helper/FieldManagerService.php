<?php

namespace Drupal\mesoestetic_ecommerce\Services\Helper;

use Drupal\Core\Entity\Entity;

class FieldManagerService {

  public function getValueField($field_name, Entity $node) {
    $field = $node->get($field_name);

    if (!empty($field) && !$field->isEmpty()) {
      $value = $field->getValue()[0]['value'];

      return $value ;
    }

    return '';
  }

  public function getReferencedField($field_name, Entity $node) {
    $field = $node->get($field_name);

    if (!empty($field) && !$field->isEmpty()) {
      $value = $field->getValue()[0]['target_id'];

      return $value ;
    }

    return '';
  }

  public function setReferencedField($field_name, Entity $node, $value) {
    $node->set($field_name, $value);
    $node->save();
  }

}
