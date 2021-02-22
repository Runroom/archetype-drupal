#!/usr/bin/env bash

# Wait for mysql to be ready (avoid if have another way to check for readyness)
sleep 10s

# Initial database dump (use with caution, it will erase the database)
# drush sql:drop --yes
# drush sql:create --yes
# drush sql:cli --yes < .docker/drupal.sql

drush deploy --yes
drush language-import

php-fpm --allow-to-run-as-root
