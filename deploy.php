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
set('composer_options', '{{composer_action}} --apcu-autoloader --no-progress --no-interaction --no-dev');

set('bin/npm', function () {
    return run('. ~/.nvm/nvm.sh && nvm use > /dev/null 2>&1 && which npm');
});

set('bin/npx', function () {
    return run('. ~/.nvm/nvm.sh && nvm use > /dev/null 2>&1 && which npx');
});

task('app', function (): void {
    cd('{{release_path}}');

    run('{{bin/composer}} symfony:dump-env prod');
})->setPrivate();

task('migrations', function (): void {
    cd('{{release_path}}');

    run('{{bin/php}} {{drush}} deploy --yes');
    run('{{bin/php}} {{drush}} language-import');
})->onRoles('production');

task('fixtures', function (): void {
    cd('{{release_path}}');

    run('{{bin/php}} {{drush}} site:install minimal --existing-config --yes');
    run('{{bin/php}} {{drush}} deploy --yes');
    run('{{bin/php}} {{drush}} language-import');
})->onRoles('staging');

task('frontend:build', function (): void {
    cd('{{release_path}}');

    run('{{bin/npm}} clean-install');
    run('{{bin/npx}} encore production');
})->setPrivate();

after('deploy:vendors', 'frontend:build');
after('frontend:build', 'app');
after('app', 'migrations');
after('app', 'fixtures');
before('deploy:symlink', 'deploy:clear_paths');
after('deploy:failed', 'deploy:unlock');

inventory('servers.yaml')
    ->user(getenv('DEPLOYER_USER'));
