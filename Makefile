DOCKER_COMPOSE=docker compose
DOCKER_COMPOSE_FILES=compose.dev.yaml

.PHONY: default
default: coverage

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
