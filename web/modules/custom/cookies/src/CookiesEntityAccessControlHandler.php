<?php

declare(strict_types=1);

namespace Drupal\cookies;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

class CookiesEntityAccessControlHandler extends EntityAccessControlHandler
{
    protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account)
    {
        switch ($operation) {
            case 'view':
                return AccessResult::allowedIfHasPermission($account, 'view published cookies entity entities');
            case 'update':
                return AccessResult::allowedIfHasPermission($account, 'edit cookies entity entities');
            case 'delete':
                return AccessResult::allowedIfHasPermission($account, 'delete cookies entity entities');
        }

        return AccessResult::neutral();
    }

    protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = null)
    {
        return AccessResult::allowedIfHasPermission($account, 'add cookies entity entities');
    }
}
