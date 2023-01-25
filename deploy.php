<?php

declare(strict_types=1);

namespace Deployer;

require 'recipe/composer.php';

set('keep_releases', 3);
set('repository', 'git@github.com:Runroom/archetype-drupal.git');
set('shared_dirs', ['web/sites/default/files']);
set('shared_files', ['web/robots.txt', '.env.local']);
set('writable_dirs', ['web/sites/default/files', 'web/sites/default/tmp', 'web/sites/default/php']);
set('clear_paths', ['.docker', '.github', 'assets', 'doc', 'tests']);

set('default_timeout', null);
set('allow_anonymous_stats', false);
set('drush', 'vendor/bin/drush');
set('composer_options', '--apcu-autoloader --no-progress --no-interaction --no-dev');

task('app', function (): void {
    cd('{{release_path}}');

    run('{{bin/composer}} symfony:dump-env');
})->hidden();

task('deployment-identifier', function (): void {
    cd('{{release_path}}');

    if (!test('[ -f .deployment-identifier ]')) {
        run("echo '<?php \$deploymentIdentifier = \"{{release_name}}\";' > {{release_path}}/.deployment-identifier");
    }
})->hidden();

task('migrations', function (): void {
    cd('{{release_path}}');

    run('{{bin/php}} {{drush}} deploy --yes');
    run('{{bin/php}} {{drush}} language-import');
})->select('stage=production');

task('fixtures', function (): void {
    cd('{{release_path}}');

    run('{{bin/php}} {{drush}} site:install minimal --existing-config --yes');

    run('{{bin/php}} {{drush}} config:import --yes');
    run('{{bin/php}} {{drush}} language-import');
    run('{{bin/php}} {{drush}} content-snapshot:import --yes');
})->select('stage=staging');

task('frontend:upload', function (): void {
    askConfirmation('Did you generate the frontend assets?');

    upload('web/themes/custom/runroom/build/', '{{release_path}}/web/themes/custom/runroom/build');
})->hidden();

after('deploy:update_code', 'deployment-identifier');
after('deploy:vendors', 'frontend:upload');
after('frontend:upload', 'app');
after('app', 'migrations');
after('app', 'fixtures');
before('deploy:symlink', 'deploy:clear_paths');
after('deploy:failed', 'deploy:unlock');

import('servers.php');
