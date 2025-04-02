DOCKER_COMPOSE=docker compose
DOCKER_COMPOSE_FILES=compose.dev.yaml

.PHONY: default
default: fix checks

.PHONY: fix
fix: phpcbf

.PHONY: checks
checks: phpcs phpmd phpstan tests

.PHONY: shell
shell:
	$(DOCKER_COMPOSE) $(foreach file, $(DOCKER_COMPOSE_FILES), -f $(file)) run --rm shell

.PHONY: tests
tests:
	$(DOCKER_COMPOSE) $(foreach file, $(DOCKER_COMPOSE_FILES), -f $(file)) run --rm phpunit

.PHONY: coverage
coverage: coverage-text

.PHONY: coverage-text
coverage-text:
	$(DOCKER_COMPOSE) $(foreach file, $(DOCKER_COMPOSE_FILES), -f $(file)) run --rm phpunit-coverage --coverage-text

.PHONY: coverage-html
coverage-html:
	$(DOCKER_COMPOSE) $(foreach file, $(DOCKER_COMPOSE_FILES), -f $(file)) run --rm phpunit-coverage --coverage-html=var/coverage

.PHONY: coverage-clover
coverage-clover:
	$(DOCKER_COMPOSE) $(foreach file, $(DOCKER_COMPOSE_FILES), -f $(file)) run --rm phpunit-coverage --coverage-clover=var/coverage/clover.xml

.PHONY: phpcs
phpcs:
	$(DOCKER_COMPOSE) $(foreach file, $(DOCKER_COMPOSE_FILES), -f $(file)) run --rm phpcs

.PHONY: phpcbf
phpcbf:
	$(DOCKER_COMPOSE) $(foreach file, $(DOCKER_COMPOSE_FILES), -f $(file)) run --rm phpcbf

.PHONY: phpmd
phpmd:
	$(DOCKER_COMPOSE) $(foreach file, $(DOCKER_COMPOSE_FILES), -f $(file)) run --rm phpmd

.PHONY: phpstan
phpstan:
	$(DOCKER_COMPOSE) $(foreach file, $(DOCKER_COMPOSE_FILES), -f $(file)) run --rm phpstan
