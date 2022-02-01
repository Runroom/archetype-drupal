<?php

declare(strict_types=1);

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

if (null !== $_SERVER['FILES_BASE_URL']) {
    $settings['file_public_base_url'] = $_SERVER['FILES_BASE_URL'];
}

if ((bool) $_SERVER['REVERSE_PROXY']) {
    $settings['reverse_proxy'] = true;
    $settings['reverse_proxy_trusted_headers'] = Request::HEADER_X_FORWARDED_ALL;

    // In case you know the reverse proxy addresses, it is better to use them instead of the general remote_addr
    $settings['reverse_proxy_addresses'] = [$_SERVER['REMOTE_ADDR']];
}

$config['system.site']['mail'] = $_SERVER['SYSTEM_EMAIL'];
$config['system.site']['mail_notification'] = $_SERVER['SYSTEM_EMAIL_NOTIFICATION'];

if ((bool) $_SERVER['SMTP_OVERRIDE']) {
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

$settings['hash_salt'] = $_SERVER['APP_SECRET'];
$settings['gtm_id'] = $_SERVER['GTM_ID'];
$settings['trusted_host_patterns'] = [$_SERVER['TRUSTED_HOST']];
$settings['cookies_default_domain'] = $_SERVER['COOKIES_DEFAULT_DOMAIN'];

// Default Settings
$settings['container_yamls'][] = $app_root . '/' . $site_path . '/services.yml';
$settings['custom_translations_directory'] = 'sites/custom_translations';
$settings['file_temp_path'] = 'sites/default/tmp';
$settings['config_sync_directory'] = '../config/base';
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
    \Drupal\Component\Assertion\Handle::register();

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
}

if ('staging' === $_SERVER['APP_ENV']) {
    $config['config_split.config_split.staging']['status'] = true;
}
