<?php

declare(strict_types=1);

namespace Drupal\base_module\Theme;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Theme\ThemeNegotiatorInterface;

final class EntityEmbedPreviewThemeNegotiator implements ThemeNegotiatorInterface
{
    public function __construct(
        private readonly AccountInterface $user,
        private readonly ConfigFactoryInterface $configFactory,
        private readonly EntityTypeManagerInterface $entityTypeManager
    ) {
    }

    public function applies(RouteMatchInterface $routeMatch): bool
    {
        return 'media.filter.preview' === $routeMatch->getRouteName()
            && $this->entityTypeManager->hasHandler('user_role', 'storage')
            && $this->user->hasPermission('view the administration theme');
    }

    public function determineActiveTheme(RouteMatchInterface $routeMatch): ?string
    {
        return $this->configFactory->get('system.theme')->get('admin');
    }
}
