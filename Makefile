UNAME := $(shell uname)

AUTOLOAD = vendor/autoload.php
CERTS_DIR = .certs
DOCKER_COMPOSE = docker-compose
DOCKER_COMPOSE_FLAGS = -f docker/docker-compose.yaml -f docker/docker-compose-dev.yaml --env-file docker/.env
MKCERT = mkcert

docker-compose = $(DOCKER_COMPOSE) $(DOCKER_COMPOSE_FLAGS) $1
docker-exec =  $(call docker-compose,exec -T app /bin/bash -c "$1")

.PHONY: up compose build halt destroy ssh certs provision composer-install composer-normalize \
		phpstan php-cs-fixer phpunit phpunit-coverage database update language-export language-import cache-rebuild

# Docker
up: compose $(AUTOLOAD)

compose: $(CERTS_DIR)
ifeq ($(UNAME), Darwin)
	SSH_AUTH_SOCK=/run/host-services/ssh-auth.sock $(call docker-compose,up -d)
else
	$(call docker-compose,up -d)
endif

build: halt
	$(call docker-compose,build)

halt:
	$(call docker-compose,stop)

destroy:
	$(call docker-compose,down --remove-orphans)

ssh:
	$(call docker-compose,exec app /bin/bash)

$(CERTS_DIR):
	$(MAKE) certs

certs:
	mkdir -p $(CERTS_DIR)
	$(MKCERT) -install
	$(MKCERT) -cert-file $(CERTS_DIR)/certificate.pem -key-file $(CERTS_DIR)/certificate-key.pem localhost

# App
$(AUTOLOAD):
	$(MAKE) provision

provision: composer-install database language-import deploy

deploy:
	$(call docker-exec,drush cache:rebuild)
	$(call docker-exec,drush updatedb -y)
	$(call docker-exec,drush config:import -y)
	$(call docker-exec,drush cache:rebuild)

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

database:
	$(call docker-exec,drush sql:drop -y)
	$(call docker-exec,drush sql:create -y)
	$(call docker-exec,drush sql:cli -y < docker/drupal.sql)

update: language-export
	$(call docker-exec,drush config:export -y)
	$(call docker-exec,drush sql:dump >| docker/drupal.sql)

language-export:
	$(call docker-exec,drush language-export)

language-import:
	$(call docker-exec,drush language-import)

cache-rebuild:
	$(call docker-exec,drush cache:rebuild)
