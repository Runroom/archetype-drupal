# Drupal

## Databases

In order to do a database dump, execute this line with the desired alias depending on the site:

```
drush sql-dump > docker/drupal.sql
```

How to import the dump.sql file?

```
drush sql-cli < docker/drupal.sql
```

## Drush deployment

To execute an Deploy of all configs, rebuild cache, update entities you should use Drush.

Outside docker:

```
make deploy
```

Inside docker:

```
drush cache:rebuild
drush updatedb -y
drush config:import -y
drush cache:rebuild
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

## Migrating from Drupal Console to Drush

If you are using Drupal Console to do config:import and you are avoiding uuids, then you might need this code in order to do a migration from Drupal Console to Drush (and from not setting uuids to setting them):

    use Symfony\Component\Yaml\Yaml;
    use Symfony\Component\Finder\Finder;

    $finder = new Finder();
    $finder->files()->in(__DIR__ . '/../../config/base/')->name('*.yml');

    foreach ($finder as $configFile) {
        $contents = Yaml::parseFile($configFile->getPathName());

        if (isset($contents['uuid'])) {
            $filename = pathinfo($configFile->getFilename(), PATHINFO_FILENAME);
            $configFactory->getEditable($filename)->set('uuid', $contents['uuid'])->save();
        }
    }

You will need to use this code on `PreConfigImportCommand.php` in order to avoid problems on the FIRST deploy using uuids. Once the first deploy is complete, you don't have to keep the code, it can be safely removed.
