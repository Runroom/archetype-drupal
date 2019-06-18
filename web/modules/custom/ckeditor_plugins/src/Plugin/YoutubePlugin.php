<?php

namespace Drupal\ckeditor_plugins\Plugin;

use Drupal\ckeditor\CKEditorPluginBase;
use Drupal\editor\Entity\Editor;

/**
 * @CKEditorPlugin(
 *   id = "youtube",
 *   label = @Translation("Youtube Plugin")
 * )
 */
class YoutubePlugin extends CKEditorPluginBase
{
    public function getButtons(): array
    {
        return [
            'Youtube' => [
                'label' => t('YouTube'),
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

    private function getLibraryPath(): ?string
    {
        return drupal_get_path('module', 'ckeditor_plugins') . '/libraries/youtube';
    }
}
