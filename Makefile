COMPOSE_FILES ?= -f compose.yaml -f compose.dev.yaml
LOGS_SERVICES ?= $(shell docker compose config --services | tr '\n' ' ')
CLEAN_OPTIONS ?= -s -f -v
COVERAGE_OPTIONS ?= --coverage-text --coverage-html=var/coverage --coverage-clover=var/coverage.xml

.PHONY: all
all: coverage phpcs phpmd phpstan deptrac

.PHONY: tests
tests:
	docker compose $(COMPOSE_FILES) run --rm phpunit

.PHONY: coverage
coverage:
	docker compose $(COMPOSE_FILES) run --rm coverage $(COVERAGE_OPTIONS)

.PHONY: console
console:
	docker compose $(COMPOSE_FILES) run --rm console bash

.PHONY: phpcs
phpcs:
	docker compose $(COMPOSE_FILES) run --rm phpcs

.PHONY: phpmd
phpmd:
	docker compose $(COMPOSE_FILES) run --rm phpmd

.PHONY: phpstan
phpstan:
	docker compose $(COMPOSE_FILES) run --rm phpstan

.PHONY: deptrac
deptrac:
	docker compose $(COMPOSE_FILES) run --rm deptrac

.PHONY: start
start:
	docker compose $(COMPOSE_FILES) up -d --wait --remove-orphans

.PHONE: stop
stop:
	docker compose $(COMPOSE_FILES) down

.PHONY: restart
restart: stop start

.PHONY: build
build:
	docker compose $(COMPOSE_FILES) build

.PHONY: logs
logs:
	docker compose $(COMPOSE_FILES) logs -f -t $(LOGS_SERVICES)

.PHONY: status
status:
	docker compose $(COMPOSE_FILES) ps

.PHONY: clean
clean: stop
	docker compose $(COMPOSE_FILES) rm $(CLEAN_OPTIONS)
