DOCKER_COMPOSE=docker compose
DOCKER_COMPOSE_FILES=compose.yaml compose.dev.yaml

.PHONY: default
default: fix checks

.PHONY: restart
restart: stop start

.PHONY: start
start:
	$(DOCKER_COMPOSE) $(foreach file, $(DOCKER_COMPOSE_FILES), -f $(file)) up -d --wait --remove-orphans

.PHONY: stop
stop:
	$(DOCKER_COMPOSE) $(foreach file, $(DOCKER_COMPOSE_FILES), -f $(file)) down --remove-orphans

.PHONY: fix
fix: phpcbf psalm-fix

.PHONY: checks
checks: phpcs phpmd phpstan psalm-check deptrac-coverage deptrac tests

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
	$(DOCKER_COMPOSE) $(foreach file, $(DOCKER_COMPOSE_FILES), -f $(file)) run --rm phpmd src,tests,bin,config,public text phpmd.xml.dist

.PHONY: phpstan
phpstan:
	$(DOCKER_COMPOSE) $(foreach file, $(DOCKER_COMPOSE_FILES), -f $(file)) run --rm phpstan

.PHONY: psalm-fix
psalm-fix:
	$(DOCKER_COMPOSE) $(foreach file, $(DOCKER_COMPOSE_FILES), -f $(file)) run --rm psalm --alter --issues=all

.PHONY: psalm-check
psalm-check:
	$(DOCKER_COMPOSE) $(foreach file, $(DOCKER_COMPOSE_FILES), -f $(file)) run --rm psalm --show-info=true

.PHONY: deptrac
deptrac:
	$(DOCKER_COMPOSE) $(foreach file, $(DOCKER_COMPOSE_FILES), -f $(file)) run --rm deptrac analyze --fail-on-uncovered --report-uncovered --cache-file=var/deptrac.cache

.PHONY: deptrac-coverage
deptrac-coverage:
	$(DOCKER_COMPOSE) $(foreach file, $(DOCKER_COMPOSE_FILES), -f $(file)) run --rm deptrac-coverage
