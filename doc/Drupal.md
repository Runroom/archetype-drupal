# Drupal

## Databases

In order to do a database dump, execute this line with the desired alias depending on the site:

```
drush sql-dump > ansible/drupal.sql
```

How to import the dump.sql file?

```
drush sql-cli < ansible/drupal.sql
```

## Drupal Console & Drush

To execute an Deploy of all config-import, rebuild cache, update entities:

```
drupal deploy
```

## Export and Import custom translations

Command to export:

```
drush language-export
```

Command to import:
```
bash drush/import-translations.bash
```

## How to import database and files from other configured environments?

Import database and files from development:

```
drush sql:sync @drupal.development @drupal.local
drush rsync @drupal.development:%files @local.local:%files
```

Note that you can not import directly between servers, you have to import to local and then import to the other server instead.
