#!/bin/bash

composer install --optimize-autoloader

cp drush/aliases.drushrc.php ~/.drush/
drush @_local -y sql-drop
drush @master -y sql-dump > /dev/null # This is to accept ssh host for the first time
drush @master -y sql-dump | drush @_local -y sqlc
drush -y rsync @master:%files @_local:%files
drush @_local -y config-import
