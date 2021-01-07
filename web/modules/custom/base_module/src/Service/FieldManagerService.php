<?php

declare(strict_types=1);

namespace Drupal\base_module\Service;

use Drupal\Core\Entity\FieldableEntityInterface;

class FieldManagerService
{
    public function getFileUrlField(string $fieldName, FieldableEntityInterface $object): ?string
    {
        try {
            $field = $object->get($fieldName);
            $fieldEntity = $field->entity;

            return $fieldEntity->url();
        } catch (\Exception $e) {
        }

        return null;
    }

    public function getValueField(string $fieldName, FieldableEntityInterface $object): ?string
    {
        $field = $object->get($fieldName);

        if (empty($field) || $field->isEmpty()) {
            return null;
        }

        return $field->getValue()[0]['value'];
    }

    public function getReferencedField(string $fieldName, FieldableEntityInterface $object): ?string
    {
        try {
            $field = $object->get($fieldName);
        } catch (\Exception $e) {
            return null;
        }

        if (empty($field) || $field->isEmpty()) {
            return null;
        }

        return $field->getValue()[0]['target_id'];
    }

    public function getReferencedFields(string $fieldName, FieldableEntityInterface $object): array
    {
        $field = $object->get($fieldName);

        if (empty($field) || $field->isEmpty()) {
            return [];
        }

        return array_map(function ($reference) {
            return $reference['target_id'];
        }, $field->getValue());
    }

    public function getEntityReferenced(string $fieldName, FieldableEntityInterface $object): ?object
    {
        try {
            $entityReferenceList = $object->get($fieldName);

            if (isset($entityReferenceList[0])) {
                $entityReferenceItem = $entityReferenceList[0];

                $entityReference = $entityReferenceItem->get('entity');
                $entityAdapter = $entityReference->getTarget();

                return $entityAdapter->getValue();
            }
        } catch (\Exception $exception) {
        }

        return null;
    }

    public function getEntitiesReferenced(string $fieldName, FieldableEntityInterface $object): ?array
    {
        $entities = [];

        try {
            $entityReferenceList = $object->get($fieldName);

            foreach ($entityReferenceList as $entityReferenceItem) {
                $entityReference = $entityReferenceItem->get('entity');
                $entityAdapter = $entityReference->getTarget();
                $entities[] = $entityAdapter->getValue();
            }
        } catch (\Exception $exception) {
            return null;
        }

        return $entities;
    }

    public function getListAllowedValues(string $fieldName, object $object): ?array
    {
        $fieldDefinition = $object->get($fieldName)->getFieldDefinition();
        if ('list_string' !== $fieldDefinition->getType()) {
            return null;
        }

        return $fieldDefinition->getItemDefinition()->getSettings()['allowed_values'];
    }
}
