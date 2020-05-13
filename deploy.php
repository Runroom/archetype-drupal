<?php

namespace Deployer;

require 'recipe/composer.php';

set('keep_releases', 3);
set('repository', 'git@github.com:Runroom/archetype-drupal.git');
set('shared_dirs', ['web/sites/default/files']);
set('shared_files', ['web/sites/custom.services.yml', 'web/sites/custom.settings.php', 'web/robots.txt']);
set('writable_dirs', ['web/sites/default/files']);
set('clear_paths', ['assets', 'doc', 'docker', 'node_modules', 'tests']);

set('default_timeout', null);
set('allow_anonymous_stats', false);
set('drupal', '{{release_path}}/vendor/bin/drupal');
set('composer_options', '{{composer_action}} --prefer-dist --apcu-autoloader --no-progress --no-interaction --no-dev');

set('bin/yarn', function () {
    return run('which yarn');
});

task('app', function () {
    run('cd {{release_path}} && {{bin/php}} {{drupal}} deploy');
    run('cd {{release_path}} && bash drush/import-translations.bash');
})->setPrivate();

task('yarn:build', function () {
    cd('{{release_path}}');

    run('. ~/.nvm/nvm.sh --no-use && nvm use && {{bin/yarn}} install --frozen-lockfile && {{bin/yarn}} encore production');
})->setPrivate();

after('deploy:vendors', 'yarn:build');
after('yarn:build', 'app');
before('deploy:publish', 'deploy:clear_paths');
after('deploy:failed', 'deploy:unlock');

inventory('servers.yaml')
    ->user(\getenv('DEPLOYER_USER'));
