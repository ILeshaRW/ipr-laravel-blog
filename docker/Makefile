# Makefile

.PHONY: help prune up down shell migration migrate seed test migration-refresh composer
.DEFAULT_GOAL := help

__DIR__ := $(dir $(abspath $(lastword $(MAKEFILE_LIST))))
ENV_FILE := '${__DIR__}.env'
DOCKER_COMPOSE_FILE := '${__DIR__}docker-compose.yml'
DOCKER_COMPOSE := 'docker-compose -f ${DOCKER_COMPOSE_FILE} --env-file ${ENV_FILE}'

help: ## Данное описание
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

prune: ## Очистка остановленных контейнеров и образов
	@docker system prune -a

up: ## Создание и запуск контейнеров
	@'${DOCKER_COMPOSE}' up -d

down: ## Остановка и удаление контейнеров
	@'${DOCKER_COMPOSE}' down

shell: ## Вход в командную строку контейнера
	@'${DOCKER_COMPOSE}' exec php-fpm env TERM=xterm bash -l

migration: ## Создание миграций
	@COMPOSE_INTERACTIVE_NO_CLI=1 '${DOCKER_COMPOSE}' exec php-fpm sh -c 'php artisan make:migration'

migration-refresh: ## Обновление базы данных
	@COMPOSE_INTERACTIVE_NO_CLI=1 '${DOCKER_COMPOSE}' exec php-fpm sh -c 'php artisan migrate:refresh --seed'

migrate: ## Запуск миграций
	@COMPOSE_INTERACTIVE_NO_CLI=1 '${DOCKER_COMPOSE}' exec php-fpm sh -c 'php artisan migrate'

seed: ## Популяция БД
	@COMPOSE_INTERACTIVE_NO_CLI=1 '${DOCKER_COMPOSE}' exec php-fpm sh -c 'php artisan db:seed'

test: ## Запуск тестов
	@COMPOSE_INTERACTIVE_NO_CLI=1 '${DOCKER_COMPOSE}' exec php-fpm sh -c 'php artisan test'

composer: ##Установка пакетов композера
	@'${DOCKER_COMPOSE}' exec php-fpm composer install

key: ##Создание ключа приложения
	@'${DOCKER_COMPOSE}' exec php-fpm php artisan key:generate
