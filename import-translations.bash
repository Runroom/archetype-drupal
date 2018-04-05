#!/bin/bash

# drush language-import --langcode=es /vagrant/web/sites/custom_translations/es.po

# BASEDIR=$(dirname "$0")
# echo "$BASEDIR"
# echo 'here'

translation_dir=$(pwd)/web/sites/custom_translations
# translation_dir_es=$translation_dir/es.po



# drush language-import --langcode=es $translation_dir_es


for entry in "$translation_dir"/*
do
  echo -----------------------
  echo Import translations for $entry
  echo -----------------------

  drush language-import --langcode=es $entry
done
