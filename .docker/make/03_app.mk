ifndef DOCKER_COMPOSE
$(error DOCKER_COMPOSE must be defined before loading make/03_app.mk)
endif

DOCKER_EXEC = $(DOCKER_COMPOSE) exec app

ssh:
	$(DOCKER_EXEC) /bin/ash
.PHONY: ssh

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