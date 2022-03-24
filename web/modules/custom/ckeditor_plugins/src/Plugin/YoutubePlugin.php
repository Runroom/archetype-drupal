<?php

declare(strict_types=1);

namespace Drupal\ckeditor_plugins\Plugin;

use Drupal\ckeditor\CKEditorPluginBase;
use Drupal\Core\Extension\ModuleExtensionList;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\editor\Entity\Editor;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @CKEditorPlugin(
 *   id = "youtube",
 *   label = @Translation("Youtube Plugin")
 * )
 */
final class YoutubePlugin extends CKEditorPluginBase
{
    public function __construct(
        array $configuration,
        string $pluginId,
        mixed $pluginDefinition,
        private readonly ModuleExtensionList $moduleExtensionList
    ) {
        parent::__construct($configuration, $pluginId, $pluginDefinition);
    }

    public static function create(
        ContainerInterface $container,
        array $configuration,
        string $pluginId,
        mixed $pluginDefinition
    ): self {
        return new static(
            $configuration,
            $pluginId,
            $pluginDefinition,
            $container->get('extension.list.module')
        );
    }

    public function getButtons(): array
    {
        return [
            'Youtube' => [
                'label' => new TranslatableMarkup('YouTube'),
                'image' => $this->getLibraryPath() . '/images/icon.png',
            ],
        ];
    }

    public function isInternal(): bool
    {
        return false;
    }

    public function getDependencies(Editor $editor): array
    {
        return [];
    }

    public function getLibraries(Editor $editor): array
    {
        return [];
    }

    public function getFile(): string
    {
        return $this->getLibraryPath() . '/plugin.js';
    }

    public function getConfig(Editor $editor): array
    {
        return [
            'youtube_disabled_fields' => ['txtWidth', 'txtHeight', 'chkResponsive', 'chkPrivacy'],
            'youtube_privacy' => true,
            'youtube_responsive' => false,
            'youtube_related' => false,
        ];
    }

    private function getLibraryPath(): string
    {
        return $this->moduleExtensionList->getPath('ckeditor_plugins') . '/libraries/youtube';
    }
}
