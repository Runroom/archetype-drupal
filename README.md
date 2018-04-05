# Archetype Drupal

## Setup

Clone repository:

    git clone git@bitbucket.org:runroom/archetype-symfony.git

Install the hostmanager plugin

    vagrant plugin install vagrant-hostmanager

Virtual machine up:

    vagrant up

## Environment

- `http://drupal.local` to view Drupal
- `http://pimpmylog.drupal.local` to view logs
- `http://adminer.drupal.local` similar to PHPMyAdmin

## Database

In order to do a database dump, execute this line with the desired alias:

```
drush sql-dump > /vagrant/drupal-vm/provisioning/files/dump.sql
```

Once the dump is completed, you can use it by executing:

```
ansible-run initialize,import
```

## Export and Import custom translations

Command to export:
```

bash export-translations.bash
```

Command to import:
```
bash import-translations.bash
```

Enjoy!

## Drupal Console

Use it for everything but database dumps

## Drush

Use it to do database dumps
