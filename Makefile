.PHONY: all
all: start

.PHONY: start
start:
	docker compose up -d --wait --remove-orphans
