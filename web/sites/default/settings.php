<?php

$config['system.logging']['error_level'] = 'hide';
$config['system.performance']['cache']['page']['max_age'] = 900;
$config['system.performance']['css']['preprocess'] = 1;
$config['system.performance']['js']['preprocess'] = 1;
$config['stage_file_proxy.settings']['origin'] = false;

$settings['update_free_access'] = false;
$settings['file_scan_ignore_directories'] = ['node_modules', 'bower_components'];
$settings['container_yamls'][] = $app_root . '/' . $site_path . '/services.yml';
$settings['rebuild_access'] = false;
$settings['skip_permissions_hardening'] = true;
$settings['container_yamls'][] = $app_root . '/sites/custom.services.yml';
$settings['custom_translations_directory'] = 'sites/custom_translations';
$settings['gtm_id'] = 'GTM-KTPM543';
$settings['file_temp_path'] = 'sites/default/files/tmp';
$settings['config_sync_directory'] = '../config/base';

include $app_root . '/sites/custom.settings.php';
