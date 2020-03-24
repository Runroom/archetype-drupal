<?php

namespace Deployer;

require 'recipe/composer.php';

set('repository', 'git@github.com:Runroom/archetype-drupal.git');
set('shared_dirs', ['var/spool', 'public/uploads']);
set('shared_files', ['.env.local', 'public/robots.txt']);
set('writable_dirs', ['var/log', 'var/cache', 'var/spool', 'public/uploads']);

set('default_timeout', null);
set('allow_anonymous_stats', false);
set('console', '{{release_path}}/bin/console');
set('composer_options', '{{composer_action}} --prefer-dist --apcu-autoloader --no-progress --no-interaction --no-dev');

set('bin/yarn', function () {
    return run('which yarn');
});

task('app', function () {
    cd('{{release_path}}');
    run('{{bin/php}} {{drupal_console}} deploy');

    cd('{{release_path}}/drush');
    run('bash import-translations.bash');
})->setPrivate();

task('yarn:build', function () {
    cd('{{release_path}}');

    if (has('previous_release')) {
        run('cp -R {{previous_release}}/node_modules {{release_path}}/node_modules');
    }

    run('. ~/.nvm/nvm.sh --no-use && nvm use && {{bin/yarn}} && {{bin/yarn}} encore production');
})->setPrivate();

after('deploy:vendors', 'yarn:build');
after('yarn:build', 'app');
after('deploy:failed', 'deploy:unlock');

inventory('servers.yaml')
    ->user(\getenv('DEPLOYER_USER'));
