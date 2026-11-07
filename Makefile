# GuardOps — common developer commands
.PHONY: demo test lint analyse up down migrate shell help

COMPOSE ?= docker compose
ARTISAN ?= php artisan
PHP ?= php

help:
	@echo "GuardOps targets:"
	@echo "  make up       - Start Docker stack"
	@echo "  make down     - Stop Docker stack"
	@echo "  make migrate  - Run database migrations"
	@echo "  make demo     - Boot stack + seed demo environment"
	@echo "  make test     - Run Pest test suite"
	@echo "  make lint     - Run Laravel Pint"
	@echo "  make analyse  - Run PHPStan (Larastan)"
	@echo "  make shell    - Open a shell in the app container"

up:
	$(COMPOSE) up -d --build

down:
	$(COMPOSE) down

migrate:
	$(COMPOSE) exec app $(ARTISAN) migrate --force

demo: up
	$(COMPOSE) exec app $(ARTISAN) key:generate --force || true
	$(COMPOSE) exec app $(ARTISAN) migrate --force
	$(COMPOSE) exec app $(ARTISAN) demo:install || $(COMPOSE) exec app $(ARTISAN) db:seed --force
	@echo "Demo ready at http://localhost:$${APP_PORT:-8080}"
	@echo "Mailpit UI: http://localhost:$${FORWARD_MAILPIT_UI_PORT:-8025}"

test:
	$(PHP) vendor/bin/pest

lint:
	$(PHP) vendor/bin/pint

analyse:
	$(PHP) vendor/bin/phpstan analyse --memory-limit=1G

shell:
	$(COMPOSE) exec app sh
