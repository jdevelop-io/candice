DOCKER_COMPOSE_FILES := deploy/dev/docker/compose.tools.yaml
DOCKER_COMPOSE := docker compose $(foreach file, $(DOCKER_COMPOSE_FILES), -f $(file))

.PHONY: help
help:
	@echo "Usage: make [target]"
	@echo ""
	@echo "Targets:"
	@echo "  help	  	 	Show this help message"
	@echo "  install/tests	Create symlink for PHPUnit configuration file (if not exists)"
	@echo "  tests			Run PHPUnit tests"
	@echo "  coverage	Run PHPUnit tests with coverage"
	@echo ""
	@echo "Use 'make help' for more information."

.PHONY: install/tests
install/tests:
	@if [ ! -f "tests/unit/config/phpunit.xml" ]; then \
		echo "Local PHPUnit configuration file not found. Creating a symlink..."; \
		ln --symbolic --relative --force tests/unit/config/phpunit.xml.dist tests/unit/config/phpunit.xml; \
		echo "Symlink tests/unit/config/phpunit.xml -> tests/unit/config/phpunit.xml.dist created."; \
	fi

.PHONY: tests
tests: install/tests
	@echo "Running PHPUnit tests..."
	@$(DOCKER_COMPOSE) run --rm phpunit --testdox --configuration tests/unit/config/phpunit.xml
	@echo "PHPUnit tests completed."

.PHONY: coverage
coverage: install/tests
	@echo "Running PHPUnit tests with coverage..."
	@$(DOCKER_COMPOSE) run --rm phpunit-coverage --testdox --configuration tests/unit/config/phpunit.xml --coverage-html tests/unit/coverage
	@echo "PHPUnit tests with coverage completed."
	@echo "Coverage report generated in tests/unit/coverage directory."
	@echo "Open tests/unit/coverage/index.html in your browser to view the report."

.PHONY: ci/coverage
ci/coverage: install/tests
	@echo "Running PHPUnit tests with coverage for CI..."
	@$(DOCKER_COMPOSE) run --rm phpunit-coverage --testdox --configuration tests/unit/config/phpunit.xml --coverage-clover tests/unit/coverage/coverage.xml
	@echo "PHPUnit tests with coverage for CI completed."
	@echo "Coverage report generated in tests/unit/coverage/coverage.xml file."
