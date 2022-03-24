<?php

declare(strict_types=1);

namespace Drupal\base_module\Service;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Field\EntityReferenceFieldItemList;
use Drupal\Core\Field\FieldItemListInterface;

final class FieldManagerService
{
    public function getValue(string $fieldName, FieldableEntityInterface $object, string $fieldValue = 'value'): ?string
    {
        if (!$object->hasField($fieldName)) {
            return null;
        }

        $values = $this->getFieldValues($object->get($fieldName), $fieldValue);

        if (null === $values || [] === $values) {
            return null;
        }

        return current($values);
    }

    /**
     * @return string[]|null
     */
    public function getValues(string $fieldName, FieldableEntityInterface $object, string $fieldValue = 'value'): ?array
    {
        if (!$object->hasField($fieldName)) {
            return null;
        }

        return $this->getFieldValues($object->get($fieldName), $fieldValue);
    }

    /**
     * @return string[]|null
     */
    public function getFieldValues(FieldItemListInterface $field, string $fieldName = 'value'): ?array
    {
        if ($field->isEmpty()) {
            return null;
        }

        $fieldValue = $field->getValue();
        $values = [];

        foreach ($fieldValue as $singleValue) {
            if (isset($singleValue[$fieldName])) {
                $values[] = $singleValue[$fieldName];
            }
        }

        return $values;
    }

    public function getEntity(string $fieldName, FieldableEntityInterface $object): ?EntityInterface
    {
        if (!$object->hasField($fieldName)) {
            return null;
        }

        $field = $object->get($fieldName);
        \assert($field instanceof EntityReferenceFieldItemList);

        $entities = $this->getFieldEntities($field);

        if (null === $entities || [] === $entities) {
            return null;
        }

        return current($entities);
    }

    /**
     * @return EntityInterface[]|null
     */
    public function getEntities(string $fieldName, FieldableEntityInterface $object): ?array
    {
        if (!$object->hasField($fieldName)) {
            return null;
        }

        $field = $object->get($fieldName);
        \assert($field instanceof EntityReferenceFieldItemList);

        return $this->getFieldEntities($field);
    }

    /**
     * @return EntityInterface[]|null
     */
    public function getFieldEntities(EntityReferenceFieldItemList $field): ?array
    {
        if ($field->isEmpty()) {
            return null;
        }

        return $field->referencedEntities();
    }

    public function getFileUrlField(string $fieldName, FieldableEntityInterface $object): ?string
    {
        if ($object->hasField($fieldName)) {
            return null;
        }

        $fieldEntity = $object->get($fieldName)->entity;

        return $fieldEntity->url();
    }

    public function getListAllowedValues(string $fieldName, FieldableEntityInterface $object): ?array
    {
        if ($object->hasField($fieldName)) {
            return null;
        }

        $fieldDefinition = $object->get($fieldName)->getFieldDefinition();

        if ('list_string' !== $fieldDefinition->getType()) {
            return null;
        }

        return $fieldDefinition->getItemDefinition()->getSettings()['allowed_values'];
    }
}
