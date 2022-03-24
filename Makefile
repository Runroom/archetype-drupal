AUTOLOAD = vendor/autoload.php
CERTS_DIR = .certs
MKCERT = mkcert
UID = $(shell id -u)
GID = $(shell id -g)

docker-exec = docker compose exec app /bin/bash -c "$1"

# Docker
up: compose $(AUTOLOAD)
.PHONY: up

compose: $(CERTS_DIR)
	docker compose up -d
.PHONY: compose

build: halt
	docker compose build --build-arg UID=$(UID) --build-arg GID=$(GID)
.PHONY: build

halt:
	docker compose stop
.PHONY: halt

destroy:
	docker compose down --remove-orphans --volumes
.PHONY: destroy

ssh:
	docker compose exec app /bin/bash
.PHONY: ssh

$(CERTS_DIR):
	$(MAKE) certs

certs:
	mkdir -p $(CERTS_DIR)
	$(MKCERT) -install
	$(MKCERT) -cert-file $(CERTS_DIR)/certificate.pem -key-file $(CERTS_DIR)/certificate-key.pem localhost
.PHONY: certs

# App
$(AUTOLOAD):
	$(MAKE) provision

provision: composer-install database deploy language-import content-import
.PHONY: provision

composer-install:
	$(call docker-exec,composer install --optimize-autoloader)
.PHONY: composer-install

composer-normalize:
	$(call docker-exec,composer normalize)
.PHONY: composer-normalize

phpstan:
	$(call docker-exec,composer phpstan)
.PHONY: phpstan

php-cs-fixer:
	$(call docker-exec,composer php-cs-fixer)
.PHONY: php-cs-fixer

phpunit:
	$(call docker-exec,phpunit)
.PHONY: phpunit

phpunit-coverage:
	$(call docker-exec,phpunit --coverage-html /usr/app/coverage)
.PHONY: phpunit-coverage

# Drupal
deploy:
	$(call docker-exec,drush deploy --yes)
.PHONY: deploy

update: language-export config-export
.PHONY: update

config-export:
	$(call docker-exec,drush config:export --yes)
.PHONY: config-export

config-import:
	$(call docker-exec,drush config:import --yes)
.PHONY: config-import

content-export:
	$(call docker-exec,drush content-snapshot:export --yes)
.PHONY: content-export

content-import:
	$(call docker-exec,drush content-snapshot:import --yes)
.PHONY: content-import

language-export:
	$(call docker-exec,drush language-export)
.PHONY: language-export

language-import:
	$(call docker-exec,drush language-import)
.PHONY: language-import

cache-clear:
	$(call docker-exec,drush cache:rebuild)
.PHONY: cache-clear

database:
	$(call docker-exec,drush site:install minimal --existing-config --yes)
.PHONY: database
