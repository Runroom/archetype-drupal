<?php

namespace Drupal\base_module\Service;

class MetadataService
{
    public const TERMINATION = '...';
    public const MAX_LENGTHS = [
        'title' => 70,
        'description' => 155,
        'og:title' => 95,
        'og:description' => 300,
        'twitter:title' => 70,
        'twitter:description' => 200,
    ];

    public function truncateMetadata(array &$attachments): void
    {
        foreach ($attachments['#attached']['html_head'] as &$tag) {
            if ($this->hasToBeTruncated($tag[0])) {
                $tag[0]['#attributes']['content'] = $this->truncate(
                    $tag[0]['#attributes']['content'],
                    self::MAX_LENGTHS[$tag[0]['#attributes'][$this->getType($tag[0])]]
                );
            }
        }
    }

    private function getType(array $tag): string
    {
        return isset($tag['#attributes']['property']) ? 'property' : 'name';
    }

    private function hasToBeTruncated(array $tag): bool
    {
        $type = $this->getType($tag);

        return $tag['#tag'] === 'meta'
            && isset($tag['#attributes'][$type])
            && isset(self::MAX_LENGTHS[$tag['#attributes'][$type]]);
    }

    private function truncate(string $content, int $length): string
    {
        if (\strlen($content) <= $length) {
            return $content;
        }

        return \substr($content, 0, $length - \strlen(self::TERMINATION)) . self::TERMINATION;
    }
}
