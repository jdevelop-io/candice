LOGS_SERVICES ?= $(shell docker compose config --services | tr '\n' ' ')
CLEAN_OPTIONS ?= -s -f -v

.PHONY: all
all: start

.PHONY: start
start:
	docker compose up -d --wait --remove-orphans

.PHONE: stop
stop:
	docker compose down

.PHONY: restart
restart: stop start

.PHONY: build
build:
	docker compose build

.PHONY: logs
logs:
	docker compose logs -f -t $(LOGS_SERVICES)

.PHONY: status
status:
	docker compose ps

.PHONY: clean
clean: stop
	docker compose rm $(CLEAN_OPTIONS)
