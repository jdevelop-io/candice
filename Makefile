.PHONY: shell
shell:
	docker compose -f deployment/local/docker/compose.tools.yaml \
	    run --rm shell

.PHONY: test_unit
test_unit:
	@if [ ! -L config/test/unit/phpunit.xml ]; then \
	    echo "Creating phpunit.xml symlink"; \
		ln -s phpunit.xml.dist config/test/unit/phpunit.xml; \
		echo "Created phpunit.xml symlink"; \
	else \
		echo "phpunit.xml symlink already exists. Skipping creation."; \
	fi

	docker compose -f deployment/local/docker/compose.tools.yaml \
	    run --rm phpunit --configuration config/test/unit/phpunit.xml
