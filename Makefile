AUTOLOAD = vendor/autoload.php
CERTS_DIR = .certs
MKCERT = mkcert

docker-exec = docker compose exec app /bin/bash -c "$1"

.PHONY: up compose build halt destroy ssh certs provision composer-install composer-normalize \
		phpstan php-cs-fixer phpunit phpunit-coverage database update config-export config-import \
		content-export content-import language-export language-import cache-rebuild

# Docker
up: compose $(AUTOLOAD)

compose: $(CERTS_DIR)
	docker compose up -d

build: halt
	docker compose build

halt:
	docker compose stop

destroy:
	docker compose down --remove-orphans --volumes

ssh:
	docker compose exec app /bin/bash

$(CERTS_DIR):
	$(MAKE) certs

certs:
	mkdir -p $(CERTS_DIR)
	$(MKCERT) -install
	$(MKCERT) -cert-file $(CERTS_DIR)/certificate.pem -key-file $(CERTS_DIR)/certificate-key.pem localhost

# App
$(AUTOLOAD):
	$(MAKE) provision

provision: composer-install database deploy language-import content-import

deploy:
	$(call docker-exec,drush deploy --yes)

composer-install:
	$(call docker-exec,composer install --optimize-autoloader)

composer-normalize:
	$(call docker-exec,composer normalize)

phpstan:
	$(call docker-exec,composer phpstan)

php-cs-fixer:
	$(call docker-exec,composer php-cs-fixer)

phpunit:
	$(call docker-exec,phpunit)

phpunit-coverage:
	$(call docker-exec,phpunit --coverage-html /usr/app/coverage)

database:
	$(call docker-exec,drush site:install minimal --existing-config --yes)

update: language-export config-export

config-export:
	$(call docker-exec,drush config:export --yes)

config-import:
	$(call docker-exec,drush config:import --yes)

content-export:
	$(call docker-exec,drush content-snapshot:export --yes)

content-import:
	$(call docker-exec,drush content-snapshot:import --yes)

language-export:
	$(call docker-exec,drush language-export)

language-import:
	$(call docker-exec,drush language-import)

cache-rebuild:
	$(call docker-exec,drush cache:rebuild)
