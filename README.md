# Archetype Drupal

## Setup

Clone repository:

    git clone git@bitbucket.org:runroom/archetype-drupal.git

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
drush sql-dump > /vagrant/ansible/provisioning/files/dump.sql
```

Once the dump is completed, you can use it by executing:

```
ansible-run initialize,import
```

How to import the dump.sql file ?

```
drush sql-cli < ansible/provisioning/files/dump.sql
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

## Estructura de Templates

The rendering approach of the templates has been based on the idea of designing and styling all the elements of the web directly from the styleguide.

In this way, as soon as styles and structure are applied to the templates of the styleguide elements, they will be added to the natural rendering process of Drupal.

All the templates of the paragraphs and fields of the nature of Drupal and the style sheet will be fed from the same point.

The same will not happen for the styles and structure of the pages and types of content. For these, the default process of Drupal will continue to be applied.

Each time a new "paragraph" component is added:
1. Its template must be overwritten and it will be extended from a twig that will be in the "organisms" directory.
2. New twig will be added to the style sheet that will be extended from the twig ism in the "organisms" directory.

Within the "templates" directory, there are several folders that help maintain this structure: styleguide, paragraph, organisms, molecules and atoms. All of them will have the same number of directories named in the same way, to facilitate correspondence between them.