ifndef DOCKER_EXEC
$(error DOCKER_EXEC must be defined before loading make/04_drupal.mk)
endif

provision: composer-install database deploy language-import content-import ## Install dependencies, clear cache, and provision database.
.PHONY: provision

deploy: ## Execute Drush deploy.
	$(DOCKER_EXEC) drush deploy --yes
.PHONY: deploy

update: language-export config-export ## Export language and configuration to files.
.PHONY: update

config-export: ## Export configuration to files.
	$(DOCKER_EXEC) drush config:export --yes
.PHONY: config-export

config-import: ## Import configuration.
	$(DOCKER_EXEC) drush config:import --yes
.PHONY: config-import

content-export: ## Generate content snapshot.
	$(DOCKER_EXEC) drush content-snapshot:export --yes
.PHONY: content-export

content-import: ## Import content snapshot.
	$(DOCKER_EXEC) drush content-snapshot:import --yes
.PHONY: content-import

language-export: ## Export language to files.
	$(DOCKER_EXEC) drush language-export
.PHONY: language-export

language-import: ## Import language.
	$(DOCKER_EXEC) drush language-import
.PHONY: language-import

cache-clear: ## Clear Drupal cache.
	$(DOCKER_EXEC) drush cache:rebuild
.PHONY: cache-clear

database: ## Install Drupal database.
	$(DOCKER_EXEC) drush site:install minimal --existing-config --yes
.PHONY: database
