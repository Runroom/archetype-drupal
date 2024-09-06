<?php

declare(strict_types=1);

use DrupalFinder\DrupalFinderComposerRuntime;
use DrupalRector\Set\Drupal10SetList;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;

return static function (RectorConfig $rectorConfig): void {
    $drupalFinder = new DrupalFinderComposerRuntime();
    $drupalRoot = $drupalFinder->getDrupalRoot();
    $rectorConfig->autoloadPaths([
        $drupalRoot . '/core',
        $drupalRoot . '/modules',
        $drupalRoot . '/profiles',
        $drupalRoot . '/themes'
    ]);

    $rectorConfig->skip(['*/upgrade_status/tests/modules/*']);
    $rectorConfig->fileExtensions(['php', 'module', 'theme', 'install', 'profile', 'inc', 'engine']);

    $rectorConfig->importNames();
    $rectorConfig->importShortClasses(false);

    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_83,
        Drupal10SetList::DRUPAL_10,
    ]);
};
