<?php

declare(strict_types=1);

namespace Drupal\cookies;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\Routing\AdminHtmlRouteProvider;
use Symfony\Component\Routing\Route;

class CookiesEntityHtmlRouteProvider extends AdminHtmlRouteProvider
{
    public function getRoutes(EntityTypeInterface $entity_type)
    {
        $collection = parent::getRoutes($entity_type);

        $entity_type_id = $entity_type->id();

        if ($settings_form_route = $this->getSettingsFormRoute($entity_type)) {
            $collection->add("$entity_type_id.settings", $settings_form_route);
        }

        return $collection;
    }

    protected function getSettingsFormRoute(EntityTypeInterface $entity_type)
    {
        if (!$entity_type->getBundleEntityType()) {
            $route = new Route("/admin/structure/{$entity_type->id()}/settings");

            $route->setDefaults([
                '_form' => 'Drupal\cookies\Form\CookiesEntitySettingsForm',
                '_title' => "{$entity_type->getLabel()} settings",
            ])
                ->setRequirement('_permission', $entity_type->getAdminPermission())
                ->setOption('_admin_route', true);

            return $route;
        }
    }
}
