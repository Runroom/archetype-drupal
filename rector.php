<?php

declare(strict_types=1);

use DrupalRector\Set\Drupal8SetList;
use DrupalRector\Set\Drupal9SetList;
use Rector\Config\RectorConfig;
use Rector\Php71\Rector\FuncCall\CountOnNullRector;
use Rector\Set\ValueObject\LevelSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/drush',
        __DIR__ . '/web/modules/custom',
        __DIR__ . '/web/themes/custom',
        __DIR__ . '/tests',
    ]);
    $rectorConfig->autoloadPaths([
        __DIR__ . '/web/core',
        __DIR__ . '/web/modules',
        __DIR__ . '/web/themes',
    ]);
    $rectorConfig->fileExtensions([
        'php',
        'module',
        'theme',
        'install',
    ]);

    $rectorConfig->importNames();
    $rectorConfig->importShortClasses(false);
    $rectorConfig->skip([
        CountOnNullRector::class,
    ]);

    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_82,
        Drupal8SetList::DRUPAL_8,
        Drupal9SetList::DRUPAL_9,
    ]);

    $parameters = $rectorConfig->parameters();
    $parameters->set('drupal_rector_notices_as_comments', true);
};
