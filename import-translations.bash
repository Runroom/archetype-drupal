#!/bin/bash

translation_dir=$(pwd)/web/sites/custom_translations

for entry in "$translation_dir"/*
do
  echo -----------------------
  echo Import translations for $entry
  echo -----------------------

  drush language-import $entry
done
