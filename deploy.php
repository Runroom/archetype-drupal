<?php

namespace Deployer;

require 'recipe/composer.php';

set('repository', 'git@bitbucket.org:runroom/archetype-drupal.git');
set('shared_dirs', ['web/sites/default/files']);
set('shared_files', ['web/sites/default/settings.php', 'web/robots.txt', 'web/.htaccess']);
set('writable_dirs', ['web/sites/default/files']);
set('clear_paths', ['web/sites/development.services.yml', 'web/sites/development.settings.php']);

set('ssh_type', 'native');
set('ssh_multiplexing', true);

set('allow_anonymous_stats', false);
set('drupal_console', '{{release_path}}/vendor/bin/drupal');
set('composer_options', '{{composer_action}} --no-dev --prefer-dist --no-progress --no-interaction --apcu-autoloader');

task('app', function () {
    run('cd {{release_path}} && {{bin/php}} {{drupal_console}} deploy');
})->setPrivate();

after('deploy:update_code', 'deploy:clear_paths');
after('deploy:vendors', 'deploy:writable');
after('deploy:writable', 'app');
after('deploy:failed', 'deploy:unlock');

serverList('servers.yml');
