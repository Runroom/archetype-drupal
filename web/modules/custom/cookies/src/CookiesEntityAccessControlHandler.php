<?php

declare(strict_types=1);

namespace Drupal\cookies;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Access\AccessResultInterface;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

final class CookiesEntityAccessControlHandler extends EntityAccessControlHandler
{
    protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account): AccessResultInterface
    {
        return match ($operation) {
            'view' => AccessResult::allowedIfHasPermission($account, 'view published cookies entity entities'),
            'update' => AccessResult::allowedIfHasPermission($account, 'edit cookies entity entities'),
            'delete' => AccessResult::allowedIfHasPermission($account, 'delete cookies entity entities'),
            default => AccessResult::neutral(),
        };
    }

    protected function checkCreateAccess(AccountInterface $account, array $context, $entityBundle = null): AccessResultInterface
    {
        return AccessResult::allowedIfHasPermission($account, 'add cookies entity entities');
    }
}
