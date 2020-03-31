# Docker
certs:
	mkdir -p ../.certs
	mkcert -install
	mkcert -cert-file ../.certs/certificate.pem -key-file ../.certs/certificate-key.pem localhost

up: certs
	docker-compose -f docker-compose.yaml -f docker-compose-dev.yaml up -d

halt:
	docker-compose stop

destroy: halt
	docker container prune -f
	docker image prune -af
	docker volume prune -f

build: halt
	docker-compose build

# App
composer-install:
	./docker-console composer install --optimize-autoloader

database:
	./docker-console drush sql:drop -y
	./docker-console drush sql:create -y
	./docker-console drush sql:cli -y < ../ansible/drupal.sql

update:
	./docker-console drush config:export -y
	./docker-console drush sql:dump >| ../ansible/drupal.sql

provision: composer-install database
	./docker-console drupal deploy
	./docker-console bash drush/import-translations.bash

phpunit:
	./docker-console phpunit

phpunit-coverage:
	./docker-console phpdbg -qrr vendor/bin/phpunit --coverage-html /srv/app/coverage

composer-normalize:
	./docker-console composer normalize

phpstan:
	./docker-console composer phpstan

php-cs-fixer:
	./docker-console composer php-cs-fixer
