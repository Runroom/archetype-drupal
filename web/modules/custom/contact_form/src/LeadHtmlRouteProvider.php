<?php

declare(strict_types=1);

namespace Drupal\contact_form;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\Routing\AdminHtmlRouteProvider;
use Symfony\Component\Routing\Route;

class LeadHtmlRouteProvider extends AdminHtmlRouteProvider
{
    public function getRoutes(EntityTypeInterface $entityType)
    {
        $collection = parent::getRoutes($entityType);

        $entityType_id = $entityType->id();

        if ($settings_form_route = $this->getSettingsFormRoute($entityType)) {
            $collection->add("$entityType_id.settings", $settings_form_route);
        }

        return $collection;
    }

    protected function getSettingsFormRoute(EntityTypeInterface $entityType): ?Route
    {
        if (!$entityType->getBundleEntityType()) {
            $route = new Route("/admin/structure/{$entityType->id()}/settings");
            $route->setDefaults([
                '_form' => 'Drupal\contact_form\Form\LeadSettingsForm',
                '_title' => "{$entityType->getLabel()} settings",
            ])
            ->setRequirement('_permission', $entityType->getAdminPermission())
            ->setOption('_admin_route', true);

            return $route;
        }

        return null;
    }
}
