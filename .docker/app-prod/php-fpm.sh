#!/usr/bin/env bash

drush deploy --yes
drush language-import

php-fpm --allow-to-run-as-root
