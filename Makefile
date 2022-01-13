UNAME := $(shell uname)

AUTOLOAD = vendor/autoload.php
CERTS_DIR = .certs
MKCERT = mkcert

docker-exec = docker-compose exec app /bin/bash -c "$1"

.PHONY: up compose build halt destroy ssh certs provision composer-install composer-normalize \
		phpstan php-cs-fixer phpunit phpunit-coverage database update language-export language-import cache-rebuild

# Docker
up: compose $(AUTOLOAD)

compose: $(CERTS_DIR)
ifeq ($(UNAME), Darwin)
	XDEBUG_CONFIG="client_host=host.docker.internal" docker-compose up -d
else
	docker-compose up -d
endif

build: halt
	docker-compose build

halt:
	docker-compose stop

destroy:
	docker-compose down --remove-orphans --volumes

ssh:
	docker-compose exec app /bin/bash

$(CERTS_DIR):
	$(MAKE) certs

certs:
	mkdir -p $(CERTS_DIR)
	$(MKCERT) -install
	$(MKCERT) -cert-file $(CERTS_DIR)/certificate.pem -key-file $(CERTS_DIR)/certificate-key.pem localhost

# App
$(AUTOLOAD):
	$(MAKE) provision

provision: composer-install database deploy language-import

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
	$(call docker-exec,drush site:install minimal --account-mail admin@localhost.com --account-pass admin --yes)

update: language-export
	$(call docker-exec,drush config:export --yes)

language-export:
	$(call docker-exec,drush language-export)

language-import:
	$(call docker-exec,drush language-import)

cache-rebuild:
	$(call docker-exec,drush cache:rebuild)
