# Drupal Executive Education & Masters en Derecho de ESADE

## Setup

Clone repository:

    git clone git@bitbucket.org:runroom/archetype-drupal.git

Install the hostmanager plugin

    vagrant plugin install vagrant-hostmanager

Virtual machine up:

    vagrant up

## Environment

- `http://drupal.local` to view Drupal site

## Databases

In order to do a database dump, execute this line with the desired alias depending on the site (exed or law):

```
drush sql-dump > /vagrant/ansible/dump.sql
```

Once the dump is completed, you can use it by executing:

```
ansible-run database
```

How to import the dump.sql file?

```
drush sql-cli < /vagrant/ansible/dump-law.sql
```

## Drupal Console & Drush

To execute an Deploy of all config-import, rebuild cache, update entities:

```
drupal deploy
```

```
drush
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
drush sql-sync @drupal.development @local.local
drush rsync @drupal.development:%files @local.local:%files
```
