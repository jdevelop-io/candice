SERVICES ?= $(shell docker compose config --services | tr '\n' ' ')

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
	docker compose logs -f -t $(SERVICES)
