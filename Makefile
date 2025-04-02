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
coverage:
	$(DOCKER_COMPOSE) $(foreach file, $(DOCKER_COMPOSE_FILES), -f $(file)) run --rm phpunit-coverage
