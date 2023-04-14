ifndef PROJECT_NAME
$(error PROJECT_NAME must be defined before loading make/02_docker.mk)
endif

ENV ?= dev
UID ?= $(shell id -u)
DOCKER_COMPOSE = docker compose --file .docker/docker-compose.yaml --file .docker/docker-compose.$(ENV).yaml

up:
	$(DOCKER_COMPOSE) up --wait
.PHONY: up

up-attach:
	$(DOCKER_COMPOSE) up
.PHONY: up-attach

up-debug:
	XDEBUG_MODE=debug $(MAKE) up
.PHONY: up-debug

build: ## Build the containers.
	$(DOCKER_COMPOSE) build --build-arg UID=$(UID)
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

pull: ## Pull the latest images.
	$(DOCKER_COMPOSE) pull
.PHONY: pull

prod:
	ENV=prod $(MAKE) build up
.PHONY: prod

dev:
	$(MAKE) build up
.PHONY: dev