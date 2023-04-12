ENV ?= dev
UID ?= $(shell id -u)
GID ?= $(shell id -g)

DOCKER_COMPOSE = docker compose --file .docker/docker-compose.yaml --file .docker/docker-compose.$(ENV).yaml
DOCKER_EXEC = $(DOCKER_COMPOSE) exec app

vendor

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

prod:
	ENV=prod $(MAKE) build up
.PHONY: prod

dev:
	$(MAKE) build up
.PHONY: dev
