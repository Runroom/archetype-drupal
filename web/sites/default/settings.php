<?php

$config_directories = [
  'sync' => '../config/base',
];

$config['system.logging']['error_level'] = 'hide';
$config['system.performance']['cache']['page']['max_age'] = 900;
$config['system.performance']['css']['preprocess'] = 1;
$config['system.performance']['js']['preprocess'] = 1;
$config['stage_file_proxy.settings']['origin'] = false;

$settings['update_free_access'] = FALSE;
$settings['file_scan_ignore_directories'] = ['node_modules', 'bower_components'];
$settings['container_yamls'][] = $app_root . '/' . $site_path . '/services.yml';
$settings['rebuild_access'] = FALSE;
$settings['skip_permissions_hardening'] = TRUE;
$settings['install_profile'] = 'minimal';
$settings['container_yamls'][] = DRUPAL_ROOT . '/sites/custom.services.yml';

include DRUPAL_ROOT . '/sites/custom.settings.php';
