<?php

namespace Drupal\base_module\Service;

use Drupal\Core\Entity\FieldableEntityInterface;

class FieldService
{
    public function getFileUrl(string $fieldName, FieldableEntityInterface $object): ?string
    {
        try {
            $field = $object->get($fieldName);
            $fieldEntity = $field->entity;

            return $fieldEntity->url();
        } catch (\Exception $e) {
        }

        return null;
    }

    public function getEntity(string $fieldName, FieldableEntityInterface $object): ?object
    {
        try {
            $entityReferenceList = $object->get($fieldName);
            $entityReferenceItem = $entityReferenceList->first();

            if (!\is_null($entityReferenceItem)) {
                $entityReference = $entityReferenceItem->get('entity');
                $entityAdapter = $entityReference->getTarget();

                return $entityAdapter->getValue();
            }
        } catch (\Exception $exception) {
        }

        return null;
    }

    public function getEntities(string $fieldName, FieldableEntityInterface $object): ?array
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

    public function getAllowedValues(string $fieldName, object $object): ?array
    {
        $fieldDefinition = $object->get($fieldName)->getFieldDefinition();

        if ($fieldDefinition->getType() !== 'list_string') {
            return null;
        }

        return $fieldDefinition->getItemDefinition()->getSettings()['allowed_values'];
    }
}
