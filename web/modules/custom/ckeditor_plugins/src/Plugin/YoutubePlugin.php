<?php

declare(strict_types=1);

namespace Drupal\ckeditor_plugins\Plugin;

use Drupal\ckeditor\CKEditorPluginBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\editor\Entity\Editor;

/**
 * @CKEditorPlugin(
 *   id = "youtube",
 *   label = @Translation("Youtube Plugin")
 * )
 */
final class YoutubePlugin extends CKEditorPluginBase
{
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
        return \Drupal::service('extension.list.module')->getPath('ckeditor_plugins') . '/libraries/youtube';
    }
}
