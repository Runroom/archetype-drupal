<?php

declare(strict_types=1);

use Drupal\Component\Assertion\Handle;
use Drupal\Component\Serialization\PhpSerialize;
use Drupal\redis\Cache\CacheBackendFactory;
use Drupal\redis\Cache\PhpRedis;
use Drupal\redis\Cache\RedisCacheTagsChecksum;
use Drupal\redis\ClientFactory;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;

(new Dotenv())->bootEnv(__DIR__ . '/../../../.env');

// Env Settings
$databases = [
    'default' => [
        'default' => [
            'database' => $_SERVER['DATABASE_NAME'],
            'username' => $_SERVER['DATABASE_USER'],
            'password' => $_SERVER['DATABASE_PASSWORD'],
            'prefix' => '',
            'host' => $_SERVER['DATABASE_HOST'],
            'port' => '',
            'namespace' => 'Drupal\\Core\\Database\\Driver\\' . $_SERVER['DATABASE_DRIVER'],
            'driver' => $_SERVER['DATABASE_DRIVER'],
        ],
    ],
];

if (null !== ($_SERVER['FILES_BASE_URL'] ?? null)) {
    $settings['file_public_base_url'] = $_SERVER['FILES_BASE_URL'];
}

if ((bool) ($_SERVER['REVERSE_PROXY'] ?? false)) {
    $settings['reverse_proxy'] = true;
    $settings['reverse_proxy_trusted_headers'] = Request::HEADER_X_FORWARDED_ALL;

    // In case you know the reverse proxy addresses, it is better to use them instead of the general remote_addr
    $settings['reverse_proxy_addresses'] = [$_SERVER['REMOTE_ADDR']];
}

if ((bool) ($_SERVER['SMTP_OVERRIDE'] ?? false)) {
    $config['system.site']['mail'] = $_SERVER['SYSTEM_EMAIL'];
    $config['system.site']['mail_notification'] = $_SERVER['SYSTEM_EMAIL_NOTIFICATION'];

    $config['swiftmailer.transport'] = [
        'smtp_host' => $_SERVER['SMTP_HOST'],
        'smtp_port' => $_SERVER['SMTP_PORT'],
        'smtp_encryption' => $_SERVER['SMTP_ENCRYPTION'],
        'smtp_credentials' => [
            'swiftmailer' => [
                'username' => $_SERVER['SMTP_USER'],
                'password' => $_SERVER['SMTP_PASSWORD'],
            ],
        ],
    ];
}

if ((bool) ($_SERVER['ENABLE_REDIS'] ?? false)) {
    $settings['redis.connection']['interface'] = 'PhpRedis';
    $settings['redis.connection']['host'] = $_SERVER['REDIS_HOST'];

    $settings['cache']['default'] = 'cache.backend.redis';
    $settings['container_yamls'][] = 'modules/contrib/redis/example.services.yml';
    $settings['container_yamls'][] = 'modules/contrib/redis/redis.services.yml';

    $class_loader->addPsr4('Drupal\\redis\\', 'modules/contrib/redis/src');

    $settings['bootstrap_container_definition'] = [
        'parameters' => [],
        'services' => [
            'redis.factory' => [
                'class' => ClientFactory::class,
            ],
            'cache.backend.redis' => [
                'class' => CacheBackendFactory::class,
                'arguments' => [
                    '@redis.factory',
                    '@cache_tags_provider.container',
                    '@serialization.phpserialize',
                ],
            ],
            'cache.container' => [
                'class' => PhpRedis::class,
                'factory' => ['@cache.backend.redis', 'get'],
                'arguments' => ['container'],
            ],
            'cache_tags_provider.container' => [
                'class' => RedisCacheTagsChecksum::class,
                'arguments' => ['@redis.factory'],
            ],
            'serialization.phpserialize' => [
                'class' => PhpSerialize::class,
            ],
        ],
    ];
}

$settings['deployment_identifier'] = \Drupal::VERSION;

$diFile = $app_root . '/../.deployment-identifier';
if (file_exists($diFile) && false !== include $diFile) {
    $settings['deployment_identifier'] = $deploymentIdentifier;
}

$settings['hash_salt'] = $_SERVER['APP_SECRET'];
$settings['gtm_id'] = $_SERVER['GTM_ID'];
$settings['trusted_host_patterns'] = [$_SERVER['TRUSTED_HOST']];
$settings['cookies_default_domain'] = $_SERVER['COOKIES_DEFAULT_DOMAIN'];

// Default Settings
$settings['container_yamls'][] = $app_root . '/' . $site_path . '/services.yml';
$settings['container_yamls'][] = $app_root . '/' . $site_path . '/monolog.services.yml';
$settings['custom_translations_directory'] = 'sites/default/custom_translations';
$settings['file_temp_path'] = 'sites/default/tmp';
$settings['config_sync_directory'] = '../config/base';
$settings['php_storage']['default']['directory'] = 'sites/default/php';
$settings['php_storage']['twig']['directory'] = 'sites/default/php';
$settings['skip_permissions_hardening'] = true;
$settings['rebuild_access'] = false;
$settings['update_free_access'] = false;
$settings['file_scan_ignore_directories'] = ['node_modules'];
$settings['extension_discovery_scan_tests'] = false;

$config['stage_file_proxy.settings']['origin'] = false;
$config['system.performance']['css']['preprocess'] = true;
$config['system.performance']['js']['preprocess'] = true;
$config['system.performance']['cache']['page']['max_age'] = 900;
$config['system.logging']['error_level'] = 'hide';

// Development Settings
if ('dev' === $_SERVER['APP_ENV']) {
    assert_options(\ASSERT_ACTIVE, true);
    Handle::register();

    $config['system.performance']['css']['preprocess'] = false;
    $config['system.performance']['js']['preprocess'] = false;
    $config['system.performance']['cache']['page']['max_age'] = 0;
    $config['system.logging']['error_level'] = 'verbose';

    $settings['cache']['bins']['render'] = 'cache.backend.null';
    $settings['cache']['bins']['discovery_migration'] = 'cache.backend.memory';
    $settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';
    $settings['cache']['bins']['page'] = 'cache.backend.null';
    $settings['container_yamls'][] = $app_root . '/' . $site_path . '/services.dev.yml';

    $config['config_split.config_split.development']['status'] = true;
} else {
    $settings['container_yamls'][] = $app_root . '/' . $site_path . '/services.prod.yml';
}

if ('staging' === $_SERVER['APP_ENV']) {
    $config['config_split.config_split.staging']['status'] = true;
}
