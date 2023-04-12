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
