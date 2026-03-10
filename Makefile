.PHONY: help install setup dev tinker migrate seed test

## Colors
BLUE := \033[36m
GREEN := \033[32m
YELLOW := \033[33m
RESET := \033[0m

help: ## Show this help message
	@echo '$(BLUE)ICSSF Conference Web - Development Commands$(RESET)'
	@echo ''
	@echo '$(YELLOW)Setup Commands:$(RESET)'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  $(BLUE)%-20s$(RESET) %s\n", $$1, $$2}' $(MAKEFILE_LIST)

install: ## Install all dependencies (PHP & npm)
	@echo '$(BLUE)Installing dependencies...$(RESET)'
	composer install
	npm install
	@echo '$(GREEN)✓ Dependencies installed$(RESET)'

setup: install ## Full setup (install + migrate + seed)
	@echo '$(BLUE)Running setup...$(RESET)'
	php artisan key:generate
	php artisan migrate
	php artisan db:seed --class=RoleSeeder
	@echo '$(GREEN)✓ Setup complete!$(RESET)'

dev: ## Start development servers (Vite + Laravel)
	@echo '$(BLUE)Starting development servers...$(RESET)'
	@echo '$(YELLOW)Make sure MySQL is running first!$(RESET)'
	bash dev-server.sh

serve: ## Start Laravel development server only
	@echo '$(BLUE)Starting Laravel server on http://localhost:8000$(RESET)'
	php artisan serve --host=127.0.0.1 --port=8000

vite: ## Start Vite development server only
	@echo '$(BLUE)Starting Vite server on http://localhost:5173$(RESET)'
	npm run dev

build: ## Build assets for production
	@echo '$(BLUE)Building assets...$(RESET)'
	npm run build
	@echo '$(GREEN)✓ Build complete$(RESET)'

migrate: ## Run database migrations
	@echo '$(BLUE)Running migrations...$(RESET)'
	php artisan migrate

migrate-fresh: ## Refresh database (DROP all tables + re-migrate)
	@echo '$(YELLOW)⚠️  This will delete all data!$(RESET)'
	php artisan migrate:refresh
	php artisan db:seed --class=RoleSeeder

seed: ## Run all seeders
	@echo '$(BLUE)Seeding database...$(RESET)'
	php artisan db:seed

seed-roles: ## Seed only roles
	@echo '$(BLUE)Seeding roles...$(RESET)'
	php artisan db:seed --class=RoleSeeder

tinker: ## Open Laravel Tinker shell
	@echo '$(BLUE)Opening Tinker shell...$(RESET)'
	@echo '$(YELLOW)Tip: Use this to test models and create data$(RESET)'
	php artisan tinker

test: ## Run tests
	@echo '$(BLUE)Running tests...$(RESET)'
	php artisan test

queue: ## Start queue worker
	@echo '$(BLUE)Starting queue worker...$(RESET)'
	php artisan queue:work

clear: ## Clear all caches
	@echo '$(BLUE)Clearing caches...$(RESET)'
	php artisan cache:clear
	php artisan config:clear
	php artisan view:clear
	npm cache clean --force
	@echo '$(GREEN)✓ Caches cleared$(RESET)'

logs: ## Tail Laravel logs
	@echo '$(BLUE)Tailing Laravel logs...$(RESET)'
	@echo '$(YELLOW)Press Ctrl+C to stop$(RESET)'
	tail -f storage/logs/laravel.log

db: ## Open database shell
	@echo '$(BLUE)Opening MySQL shell...$(RESET)'
	mysql -u root -p -D icssf_conference

fresh-install: ## Fresh install from scratch
	@echo '$(BLUE)Fresh install...$(RESET)'
	@read -p "This will delete all data. Continue? (y/n) " -n 1 -r; \
	echo; \
	if [[ $$REPLY =~ ^[Yy]$$ ]]; then \
		composer install; \
		npm install; \
		php artisan key:generate; \
		php artisan migrate:refresh; \
		php artisan db:seed --class=RoleSeeder; \
		echo '$(GREEN)✓ Fresh install complete$(RESET)'; \
	fi

help-php: ## Show PHP artisan commands
	@php artisan list

help-npm: ## Show npm scripts
	@npm run

watch: ## Watch for file changes (useful for Tailwind)
	@echo '$(BLUE)Watching for file changes...$(RESET)'
	npm run dev

status: ## Show server status
	@echo '$(BLUE)Server Status:$(RESET)'
	@echo '  Laravel Port 8000: ' $$(nc -z 127.0.0.1 8000 && echo '✓ Running' || echo '✗ Not running')
	@echo '  Vite Port 5173:    ' $$(nc -z 127.0.0.1 5173 && echo '✓ Running' || echo '✗ Not running')
	@echo '  MySQL:             ' $$(nc -z 127.0.0.1 3306 && echo '✓ Running' || echo '✗ Not running')

.DEFAULT_GOAL := help
