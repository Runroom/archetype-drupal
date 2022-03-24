<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Php71\Rector\FuncCall\CountOnNullRector;
use Rector\Set\ValueObject\LevelSetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [
        __DIR__ . '/drush',
        __DIR__ . '/web/modules/custom',
        __DIR__ . '/web/themes/custom',
        __DIR__ . '/tests',
    ]);
    $parameters->set(Option::AUTOLOAD_PATHS, [
        __DIR__ . '/web/core',
        __DIR__ . '/web/modules',
        __DIR__ . '/web/themes',
    ]);
    $parameters->set(Option::FILE_EXTENSIONS, [
        'php',
        'module',
        'theme',
        'install',
    ]);
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);
    $parameters->set(Option::IMPORT_SHORT_CLASSES, false);
    $parameters->set(Option::SKIP, [
        CountOnNullRector::class,
    ]);

    $containerConfigurator->import(LevelSetList::UP_TO_PHP_81);
    $containerConfigurator->import(__DIR__ .  '/vendor/palantirnet/drupal-rector/config/drupal-9/drupal-9-all-deprecations.php');
};
