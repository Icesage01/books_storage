.PHONY: help install build up down restart logs clean ps shell

help:
	@echo "Доступные команды:"
	@echo "  make install  - Полная установка (клонирование, настройка, запуск)"
	@echo "  make build    - Собрать образы"
	@echo "  make up       - Запустить контейнеры"
	@echo "  make down     - Остановить контейнеры"
	@echo "  make restart  - Перезапустить контейнеры"
	@echo "  make logs     - Показать логи"
	@echo "  make ps       - Статус контейнеров"
	@echo "  make shell    - Войти в контейнер приложения"
	@echo "  make clean    - Очистить все контейнеры и образы"
	@echo "  make mysql    - Подключиться к MySQL"
	@echo "  make redis    - Подключиться к Redis"

install:
	@if [ ! -f .env ]; then \
		if [ -f .env.example ]; then \
			cp .env.example .env; \
			echo "Файл .env создан"; \
		else \
			echo "Файл .env.example не найден. Создайте .env вручную!"; \
			exit 1; \
		fi; \
	fi
	@make build
	@make up
	@echo "Ждем запуска сервисов (sleep 10)"
	@sleep 10
	@make ps
	@echo ""
	@echo "Приложение доступно по адресу: http://localhost:8000"
	@echo ""
	@echo "Полезные команды:"
	@echo "  make logs     - Просмотр логов"
	@echo "  make shell    - Войти в контейнер"
	@echo "  make down     - Остановить приложение"

build:
	docker-compose build

up:
	docker-compose up -d

down:
	docker-compose down

restart: down up

logs:
	docker-compose logs -f

logs-app:
	docker-compose logs -f app

logs-mysql:
	docker-compose logs -f mysql

logs-redis:
	docker-compose logs -f redis

ps:
	docker-compose ps

shell:
	docker-compose exec app bash

mysql:
	docker-compose exec mysql mysql -u books_user -pbooks_password books_storage

redis:
	docker-compose exec redis redis-cli

clean:
	docker-compose down -v --rmi all --remove-orphans
	docker system prune -f

clean-all:
	docker-compose down -v --rmi all --remove-orphans
	docker system prune -af
	docker volume prune -f

# Полная очистка (включая volumes)