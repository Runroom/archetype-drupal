<?php

declare(strict_types=1);

$databases = [];

$settings['hash_salt'] = '';
$settings['update_free_access'] = false;
$settings['container_yamls'][] = $app_root . '/' . $site_path . '/services.yml';
$settings['file_scan_ignore_directories'] = ['node_modules', 'bower_components'];
$settings['entity_update_batch_size'] = 50;
$settings['entity_update_backup'] = true;
$settings['migrate_node_migrate_type_classic'] = false;
