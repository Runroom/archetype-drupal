<?php

declare(strict_types=1);

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

    private RequestStack $requestStack;
    private ConfigFactory $configFactory;

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

    /** @throws \RuntimeException if there is no request */
    public function attachMetadata(
        array &$page,
        string $title,
        string $description,
        string $imageUrl = null
    ): void {
        $request = $this->requestStack->getCurrentRequest();

        if (null === $request) {
            throw new \RuntimeException('There is no request.');
        }

        $requestUri = $request->getRequestUri();

        $title = $title . ' | ' . $this->configFactory->get('system.site')->get('name');
        $description = strip_tags($description);

        $metadata = [
            'title' => $title,
            'description' => $description,
            'og:type' => 'website',
            'og:url' => $requestUri,
            'og:title' => $title,
            'og:description' => $description,
            'og:image' => $imageUrl,
            'twitter:card' => 'summary',
            'twitter:url' => $requestUri,
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

        return 'meta' === $tag['#tag']
            && isset($tag['#attributes'][$type], self::MAX_LENGTHS[$tag['#attributes'][$type]]);
    }

    private function truncate(string $content, int $length): string
    {
        if (\strlen($content) <= $length) {
            return $content;
        }

        return substr($content, 0, $length - \strlen(self::TERMINATION)) . self::TERMINATION;
    }
}
