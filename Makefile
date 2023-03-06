ENV ?= dev
NODE_MODULES_DIR = node_modules
CERTS_DIR = .docker/traefik/certs
UID ?= $(shell id -u)
GID ?= $(shell id -g)

DOCKER_COMPOSE = docker compose --file .docker/docker-compose.yaml --file .docker/docker-compose.$(ENV).yaml
DOCKER_EXEC = $(DOCKER_COMPOSE) exec app

# Default
default: $(NODE_MODULES_DIR) $(CERTS_DIR) build up provision
.PHONY: default

# Docker
up:
	$(DOCKER_COMPOSE) up --wait
.PHONY: up

up-attach:
	$(DOCKER_COMPOSE) up
.PHONY: up-attach

up-debug:
	XDEBUG_MODE=debug $(MAKE) up
.PHONY: up-debug

build:
	$(DOCKER_COMPOSE) build --build-arg UID=$(UID) --build-arg GID=$(GID)
.PHONY: build

halt:
	$(DOCKER_COMPOSE) stop
.PHONY: halt

destroy:
	$(DOCKER_COMPOSE) down --remove-orphans --volumes
.PHONY: destroy

ps:
	$(DOCKER_COMPOSE) ps
.PHONY: ps

logs:
	$(DOCKER_COMPOSE) logs --follow
.PHONY: logs

ssh:
	$(DOCKER_EXEC) /bin/ash
.PHONY: ssh

$(NODE_MODULES_DIR):
	mkdir --parents $(NODE_MODULES_DIR)

$(CERTS_DIR):
	$(MAKE) certs

certs:
	mkdir -p $(CERTS_DIR)
	mkcert -install
	mkcert -cert-file $(CERTS_DIR)/cert.crt -key-file $(CERTS_DIR)/cert.key localhost
.PHONY: certs

# Environments
prod:
	ENV=prod $(MAKE) build up
.PHONY: prod

dev:
	$(MAKE) build up
.PHONY: dev

# App
provision: composer-install database deploy language-import content-import
.PHONY: provision

composer-install:
	$(DOCKER_EXEC) composer install --optimize-autoloader
.PHONY: composer-install

composer-normalize:
	$(DOCKER_EXEC) composer normalize
.PHONY: composer-normalize

phpstan:
	$(DOCKER_EXEC) composer phpstan
.PHONY: phpstan

php-cs-fixer:
	$(DOCKER_EXEC) composer php-cs-fixer
.PHONY: php-cs-fixer

phpunit:
	$(DOCKER_EXEC) phpunit
.PHONY: phpunit

phpunit-coverage:
	$(DOCKER_EXEC) phpunit --coverage-html /usr/app/coverage
.PHONY: phpunit-coverage

# Drupal
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
