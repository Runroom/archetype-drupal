<?php

$databases = [
  'default' => [
    'default' => [
      'database' => 'drupal',
      'username' => 'drupal',
      'password' => 'drupal',
      'prefix' => '',
      'host' => 'localhost',
      'port' => '',
      'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
      'driver' => 'mysql',
    ],
  ],
];

$config_directories = [
  'sync' => '../config/base',
];

$config['system.logging']['error_level'] = 'hide';
$config['system.performance']['cache']['page']['max_age'] = 900;
$config['system.performance']['css']['preprocess'] = 1;
$config['system.performance']['js']['preprocess'] = 1;
$config['stage_file_proxy.settings']['origin'] = false;

$settings['hash_salt'] = '_2iTYTP4sKXrIutDsoT9ZaNAAAuXC34f5VC6OZjs7HmVPnoRu5q9N5gFFqvn7cgGWSEZwZd29Q';
$settings['update_free_access'] = FALSE;
$settings['file_scan_ignore_directories'] = ['node_modules', 'bower_components'];
$settings['container_yamls'][] = $app_root . '/' . $site_path . '/services.yml';
$settings['rebuild_access'] = FALSE;
$settings['skip_permissions_hardening'] = TRUE;
$settings['trusted_host_patterns'] = ['^drupal\.local$'];
$settings['install_profile'] = 'minimal';

include $app_root . '/' . $site_path . '/development.settings.php';
