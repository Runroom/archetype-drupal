<?php

$databases = [
  'default' => [
    'default' => [
      'database' => '{{ mysql.database }}',
      'username' => '{{ mysql.user }}',
      'password' => '{{ mysql.password }}',
      'prefix' => '',
      'host' => 'localhost',
      'port' => '',
      'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
      'driver' => 'mysql',
    ],
  ],
];

$settings['trusted_host_patterns'] = ['^{{ webserver.name|replace('.', '\.') }}$'];
$settings['hash_salt'] = '308d47268412acc4fe69f0afaf00b3e63cc7c69f6d94c62d4530d045cd911839';

assert_options(ASSERT_ACTIVE, TRUE);
\Drupal\Component\Assertion\Handle::register();

$config['system.performance']['cache']['page']['max_age'] = 0;
$config['system.performance']['css']['preprocess'] = FALSE;
$config['system.performance']['js']['preprocess'] = FALSE;
$config['system.logging']['error_level'] = 'verbose';

$settings['cache']['bins']['render'] = 'cache.backend.null';
$settings['cache']['bins']['discovery_migration'] = 'cache.backend.memory';
$settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';
$settings['cache']['bins']['page'] = 'cache.backend.null';
$settings['extension_discovery_scan_tests'] = FALSE;
