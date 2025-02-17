<?php

declare(strict_types=1);

namespace Drupal\country_header;

use Drupal\Core\Database\Database;
use Drupal\Core\Logger\RfcLogLevel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

final class CountryHeaderMiddleware implements HttpKernelInterface
{
    private HttpKernelInterface $app;

    public function __construct(HttpKernelInterface $app)
    {
        $this->app = $app;
    }

    public function handle(Request $request, int $type = self::MAIN_REQUEST, bool $catch = true): Response
    {
        $response = $this->app->handle($request, $type, $catch);

        $headers = $request->headers->all();

        // Log directly to watchdog table
        $database = Database::getConnection();
        $database->insert('watchdog')
            ->fields([
                'uid' => \Drupal::currentUser()->id(),
                'type' => 'cloudfront_headers',
                'message' => '@message',
                'variables' => serialize(['@message' => print_r($headers, true)]),
                'severity' => RfcLogLevel::NOTICE,
                'link' => '',
                'location' => $request->getRequestUri(),
                'referer' => $request->headers->get('referer'),
                'hostname' => $request->getClientIp(),
                'timestamp' => time(),
            ])
            ->execute();

        $this->cleanOldLogs('cloudfront_headers', 50);

        return $response;
    }

    /**
     * Elimina registros antiguos si superan el límite especificado.
     */
    private function cleanOldLogs(string $channel, int $limit): void
    {
        $database = \Drupal::database();

        $query = $database->select('watchdog', 'w')
            ->condition('w.type', $channel)
            ->countQuery()
            ->execute()
            ->fetchField();

        if ($query > $limit) {
            $delete_query = $database->select('watchdog', 'w')
                ->fields('w', ['wid'])
                ->condition('w.type', $channel)
                ->orderBy('timestamp', 'ASC')
                ->range(0, $query - $limit)
                ->execute();

            $ids_to_delete = $delete_query->fetchCol();

            if (!empty($ids_to_delete)) {
                $database->delete('watchdog')
                    ->condition('wid', $ids_to_delete, 'IN')
                    ->execute();
            }
        }
    }
}
