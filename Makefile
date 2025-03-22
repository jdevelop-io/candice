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
