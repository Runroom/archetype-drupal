#!/usr/bin/env bash

# Can be used on staging environments to destroy the database each time you deploy
# the application, to ensure you start with the initial data each time
if [ "${RESET_DATABASE:-}" = true ]; then
    echo 'Resetting database...'

    drush site:install minimal --existing-config --yes
    drush config:import --yes
    drush language-import
    drush content-snapshot:import --yes

    # If your infrastructure allows to run sidecar containers or jobs, you might want to exit here.
    # exit 0
fi

# Can be used on production environments to apply migrations to the database each time
# you deploy the application, to ensure you start with the database in a correct state
if [ "${MIGRATE_DATABASE:-}" = true ]; then
    echo 'Applying migrations to database...'

    drush deploy --yes
    drush language-import

    # If your infrastructure allows to run sidecar containers or jobs, you might want to exit here.
    # exit 0
fi

# Can be applied on production environments to run the Drupal Cronjob.
# This job does multiple tasks since contrib modules can hook to execute task with it.
if [ "${ENABLE_CRON:-}" = true ]; then
    echo "Installing cron..."

    # Make sure the environment variables are available for crontab tasks
    declare -p | grep -Ev 'BASHOPTS|BASH_VERSINFO|EUID|PPID|SHELLOPTS|UID' > /container.env

    crontab /usr/app/.docker/app-prod/drupal-cron.crontab
    cron -f
fi

php-fpm
