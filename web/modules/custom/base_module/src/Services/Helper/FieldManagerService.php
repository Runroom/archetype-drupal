<?php

namespace Drupal\mesoestetic_ecommerce\Services\Helper;

use Drupal\Core\Entity\Entity;

class FieldManagerService
{
    public function getValueField(string $fieldName, Entity $node)
    {
        $field = $node->get($fieldName);

        if (!empty($field) && !$field->isEmpty()) {
            $value = $field->getValue()[0]['value'];

            return $value;
        }

        return '';
    }

    public function getReferencedField(string $fieldName, Entity $node)
    {
        $field = $node->get($fieldName);

        if (!empty($field) && !$field->isEmpty()) {
            $value = $field->getValue()[0]['target_id'];

            return $value;
        }

        return '';
    }

    public function setReferencedField(string $fieldName, Entity $node, $value)
    {
        $node->set($fieldName, $value);
        $node->save();
    }
}
