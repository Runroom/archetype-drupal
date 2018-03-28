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

assert_options(ASSERT_ACTIVE, TRUE);
\Drupal\Component\Assertion\Handle::register();

$config['system.performance']['cache']['page']['max_age'] = 0;
$config['system.performance']['css']['preprocess'] = FALSE;
$config['system.performance']['js']['preprocess'] = FALSE;
$config['system.logging']['error_level'] = 'verbose';

$settings['trusted_host_patterns'] = ['^drupal\.local$'];
$settings['hash_salt'] = '_2iTYTP4sKXrIutDsoT9ZaNAAAuXC34f5VC6OZjs7HmVPnoRu5q9N5gFFqvn7cgGWSEZwZd29Q';
$settings['cache']['bins']['render'] = 'cache.backend.null';
$settings['cache']['bins']['discovery_migration'] = 'cache.backend.memory';
$settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';
$settings['cache']['bins']['page'] = 'cache.backend.null';
$settings['extension_discovery_scan_tests'] = FALSE;
