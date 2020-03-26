<?php

namespace Drupal\base_module\Service;

use Drupal\Core\Config\ConfigFactory;
use Symfony\Component\HttpFoundation\RequestStack;

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
    protected $requestStack;
    protected $configFactory;

    public function __construct(
        RequestStack $requestStack,
        ConfigFactory $configFactory
    ) {
        $this->requestStack = $requestStack;
        $this->configFactory = $configFactory;
    }

    public function truncateMetadata(array &$page): void
    {
        foreach ($page['#attached']['html_head'] as &$tag) {
            if ($this->hasToBeTruncated($tag[0])) {
                $tag[0]['#attributes']['content'] = $this->truncate(
                    $tag[0]['#attributes']['content'],
                    self::MAX_LENGTHS[$tag[0]['#attributes'][$this->getType($tag[0])]]
                );
            }
        }
    }

    public function attachMetadata(
        array &$page,
        string $title,
        string $description,
        string $imageUrl = null
    ): void {
        $url = $this->requestStack->getCurrentRequest()->getRequestUri();

        $title = $title . ' | ' . $this->configFactory->get('system.site')->get('name');
        $description = \strip_tags($description);

        $metadata = [
            'title' => $title,
            'description' => $description,
            'og:type' => 'website',
            'og:url' => $url,
            'og:title' => $title,
            'og:description' => $description,
            'og:image' => $imageUrl,
            'twitter:card' => 'summary',
            'twitter:url' => $url,
            'twitter:title' => $title,
            'twitter:description' => $description,
            'twitter:image' => $imageUrl,
        ];

        foreach ($metadata as $tag => $content) {
            $page['#attached']['html_head'][] = [[
                '#tag' => 'meta',
                '#attributes' => [
                    'property' => $tag,
                    'content' => $content,
                ],
            ], $tag];
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
