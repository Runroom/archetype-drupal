<?php

declare(strict_types=1);

namespace Deployer;

require 'recipe/composer.php';

set('keep_releases', 3);
set('repository', 'git@github.com:Runroom/archetype-drupal.git');
set('shared_dirs', ['web/sites/default/files']);
set('shared_files', ['web/robots.txt', '.env.local']);
set('writable_dirs', ['web/sites/default/files', 'web/sites/default/tmp', 'web/sites/default/php']);
set('clear_paths', ['assets', 'doc', '.docker', 'node_modules', 'tests']);

set('default_timeout', null);
set('allow_anonymous_stats', false);
set('drush', 'vendor/bin/drush');
set('composer_options', '--apcu-autoloader --no-progress --no-interaction --no-dev');

set('bin/npm', function () {
    return run('. ~/.nvm/nvm.sh && nvm use > /dev/null 2>&1 && which npm');
});

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

task('frontend:build', function (): void {
    cd('{{release_path}}');

    run('{{bin/npm}} clean-install');
    run('{{bin/npm}} run build');
})->hidden();

after('deploy:update_code', 'deployment-identifier');
after('deploy:vendors', 'frontend:build');
after('frontend:build', 'app');
after('app', 'migrations');
after('app', 'fixtures');
before('deploy:symlink', 'deploy:clear_paths');
after('deploy:failed', 'deploy:unlock');

import('servers.php');
