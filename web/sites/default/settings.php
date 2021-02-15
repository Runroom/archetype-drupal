<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

(new Dotenv())->bootEnv(__DIR__ . '/../../../.env');

// Env Settings
$databases = [
  'default' => [
    'default' => [
      'database' => $_SERVER['MYSQL_DATABASE'],
      'username' => $_SERVER['MYSQL_USER'],
      'password' => $_SERVER['MYSQL_PASSWORD'],
      'prefix' => '',
      'host' => $_SERVER['MYSQL_HOST'],
      'port' => '',
      'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
      'driver' => 'mysql',
    ],
  ],
];

$settings['hash_salt'] = $_SERVER['APP_SECRET'];
$settings['gtm_id'] = $_SERVER['GTM_ID'];
$settings['trusted_host_patterns'] = [$_SERVER['TRUSTED_HOST']];
$settings['cookies_default_domain'] = $_SERVER['COOKIES_DEFAULT_DOMAIN'];

// Default Settings
$settings['container_yamls'][] = $app_root . '/' . $site_path . '/services.yml';
$settings['custom_translations_directory'] = 'sites/custom_translations';
$settings['file_temp_path'] = 'sites/default/files/tmp';
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
