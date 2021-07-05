<?php

declare(strict_types=1);

namespace Drupal\cookies;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\Routing\AdminHtmlRouteProvider;
use Symfony\Component\Routing\Route;

class CookiesEntityHtmlRouteProvider extends AdminHtmlRouteProvider
{
    public function getRoutes(EntityTypeInterface $entityType)
    {
        $collection = parent::getRoutes($entityType);

        if ($settings_form_route = $this->getSettingsFormRoute($entityType)) {
            $collection->add($entityType->id() . '.settings', $settings_form_route);
        }

        return $collection;
    }

    protected function getSettingsFormRoute(EntityTypeInterface $entityType): ?Route
    {
        if (!$entityType->getBundleEntityType()) {
            $route = new Route("/admin/structure/{$entityType->id()}/settings");

            $route->setDefaults([
                '_form' => 'Drupal\cookies\Form\CookiesEntitySettingsForm',
                '_title' => "{$entityType->getLabel()} settings",
            ]);

            $adminPermission = $entityType->getAdminPermission();

            if (\is_string($adminPermission)) {
                $route->setRequirement('_permission', $adminPermission);
            }

            $route->setOption('_admin_route', true);

            return $route;
        }

        return null;
    }
}
