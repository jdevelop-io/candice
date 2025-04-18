DOCKER_COMPOSE_FILES := deploy/dev/docker/compose.tools.yaml
DOCKER_COMPOSE := docker compose $(foreach file, $(DOCKER_COMPOSE_FILES), -f $(file))

.PHONY: help
help:
	@echo "Usage: make [target]"
	@echo ""
	@echo "Targets:"
	@echo "  help	  	 	Show this help message"
	@echo "  install/phpunit	Create symlink for PHPUnit configuration file (if not exists)"
	@echo "  tests			Run all tests"
	@echo "  tests/phpunit		Run PHPUnit tests"
	@echo ""
	@echo "Use 'make help' for more information."

.PHONY: install/phpunit
install/phpunit:
	@if [ ! -f "tests/unit/config/phpunit.xml" ]; then \
		echo "Local PHPUnit configuration file not found. Creating a symlink..."; \
		ln --symbolic --relative --force tests/unit/config/phpunit.xml.dist tests/unit/config/phpunit.xml; \
		echo "Symlink tests/unit/config/phpunit.xml -> tests/unit/config/phpunit.xml.dist created."; \
	fi

.PHONY: tests
tests: tests/phpunit

.PHONY: tests/phpunit
tests/phpunit: install/phpunit
	@echo "Running PHPUnit tests..."
	@$(DOCKER_COMPOSE) run --rm phpunit --testdox --configuration tests/unit/config/phpunit.xml
	@echo "PHPUnit tests completed."
