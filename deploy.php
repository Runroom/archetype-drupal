<?php

namespace Deployer;

require 'recipe/composer.php';

set('repository', 'git@bitbucket.org:runroom/archetype-drupal.git');
set('shared_dirs', ['web/sites/default/files']);
set('shared_files', ['web/sites/default/settings.php', 'web/robots.txt', 'web/.htaccess']);
set('writable_dirs', ['web/sites/default/files']);

set('ssh_type', 'native');
set('ssh_multiplexing', true);

set('allow_anonymous_stats', false);
set('drupal_console', '{{release_path}}/vendor/bin/drupal');
set('composer_options', '{{composer_action}} --no-dev --prefer-dist --no-progress --no-interaction --classmap-authoritative');

task('app', function () {
    run('{{bin/php}} {{drupal_console}} cache:rebuild -y --root={{release_path}}/web');
    run('{{bin/php}} {{drupal_console}} update:execute -y --root={{release_path}}/web');
    run('{{bin/php}} {{drupal_console}} config:import -y --root={{release_path}}/web');
    run('{{bin/php}} {{drupal_console}} update:entities -y --root={{release_path}}/web');
})->setPrivate();

after('deploy:vendors', 'deploy:writable');
after('deploy:writable', 'app');
after('deploy:failed', 'deploy:unlock');

serverList('servers.yml');
