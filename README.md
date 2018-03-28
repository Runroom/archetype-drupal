# Archetype Drupal

## Database

```
drush sql-dump --structure-tables-list=cache* > /vagrant/drupal-vm/provisioning/files/dump.sql
```

```
ansible-run initialize,import
```
