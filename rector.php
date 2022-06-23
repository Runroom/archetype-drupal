<?php

declare(strict_types=1);

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
    $rectorConfig->disableImportShortClasses();
    $rectorConfig->skip([
        CountOnNullRector::class,
    ]);

    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_81,
        __DIR__ .  '/vendor/palantirnet/drupal-rector/config/drupal-9/drupal-9-all-deprecations.php',
    ]);
};
