default: setup build up provision
.PHONY: default

provision: composer-install database deploy language-import content-import
.PHONY: provision

include .docker/setup.mk
include .docker/docker.mk
include .docker/php.mk
include .docker/drupal.mk
