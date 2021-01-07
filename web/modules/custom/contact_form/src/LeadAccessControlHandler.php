<?php

declare(strict_types=1);

namespace Drupal\contact_form;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\Core\Session\AccountInterface;

class LeadAccessControlHandler extends EntityAccessControlHandler
{
    protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account)
    {
        switch ($operation) {
            case 'view':
                if ($entity instanceof EntityPublishedInterface && !$entity->isPublished()) {
                    return AccessResult::allowedIfHasPermission($account, 'view unpublished lead entities');
                }

                return AccessResult::allowedIfHasPermission($account, 'view published lead entities');
            case 'update':
                return AccessResult::allowedIfHasPermission($account, 'edit lead entities');
            case 'delete':
                return AccessResult::allowedIfHasPermission($account, 'delete lead entities');
        }

        return AccessResult::neutral();
    }

    protected function checkCreateAccess(AccountInterface $account, array $context, $entityBundle = null)
    {
        return AccessResult::allowedIfHasPermission($account, 'add lead entities');
    }
}
