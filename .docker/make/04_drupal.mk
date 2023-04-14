ifndef DOCKER_EXEC
$(error DOCKER_EXEC must be defined before loading make/04_drupal.mk)
endif

deploy:
	$(DOCKER_EXEC) drush deploy --yes
.PHONY: deploy

update: language-export config-export
.PHONY: update

config-export:
	$(DOCKER_EXEC) drush config:export --yes
.PHONY: config-export

config-import:
	$(DOCKER_EXEC) drush config:import --yes
.PHONY: config-import

content-export:
	$(DOCKER_EXEC) drush content-snapshot:export --yes
.PHONY: content-export

content-import:
	$(DOCKER_EXEC) drush content-snapshot:import --yes
.PHONY: content-import

language-export:
	$(DOCKER_EXEC) drush language-export
.PHONY: language-export

language-import:
	$(DOCKER_EXEC) drush language-import
.PHONY: language-import

cache-clear:
	$(DOCKER_EXEC) drush cache:rebuild
.PHONY: cache-clear

database:
	$(DOCKER_EXEC) drush site:install minimal --existing-config --yes
.PHONY: database

provision: composer-install database deploy language-import content-import
.PHONY: provision