<?php

declare(strict_types=1);

namespace Deployer;

require 'recipe/composer.php';

set('keep_releases', 3);
set('repository', 'git@github.com:Runroom/archetype-drupal.git');
set('shared_dirs', ['web/sites/default/files']);
set('shared_files', ['web/robots.txt', '.env.local']);
set('writable_dirs', ['web/sites/default/files', 'web/sites/default/tmp']);
set('clear_paths', ['assets', 'doc', '.docker', 'node_modules', 'tests']);

set('default_timeout', null);
set('allow_anonymous_stats', false);
set('drush', '{{release_path}}/vendor/bin/drush');
set('composer_options', '{{composer_action}} --prefer-dist --apcu-autoloader --no-progress --no-interaction --no-dev');

set('bin/yarn', function () {
    return locateBinaryPath('yarn');
});

// @todo: Remove this once all projects runs on php 8.0
set('bin/composer', function () {
    return '{{bin/php}} ' . locateBinaryPath('composer');
});

// @todo: Remove this once all projects runs on php 8.0
set('bin/php', function () {
    return locateBinaryPath('php8.0');
});

task('app', function (): void {
    cd('{{release_path}}');

    run('{{bin/php}} {{bin/composer}} symfony:dump-env prod');
    run('{{bin/php}} {{drush}} deploy --yes');
    run('{{bin/php}} {{drush}} language-import');
})->setPrivate();

task('yarn:build', function (): void {
    cd('{{release_path}}');

    run('. ~/.nvm/nvm.sh --no-use && nvm use && {{bin/yarn}} install --immutable && {{bin/yarn}} encore production');
})->setPrivate();

after('deploy:vendors', 'yarn:build');
after('yarn:build', 'app');
before('deploy:symlink', 'deploy:clear_paths');
after('deploy:failed', 'deploy:unlock');

inventory('servers.yaml')
    ->user(getenv('DEPLOYER_USER'));
