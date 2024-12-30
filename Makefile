DOCKER_COMPOSE = docker compose
DOCKER_COMPOSE_DATABASE_EXEC = ${DOCKER_COMPOSE} exec database

dc_build:
	${DOCKER_COMPOSE} build

dc_up:
	${DOCKER_COMPOSE} up -d --remove-orphans

dc_ps:
	${DOCKER_COMPOSE} ps -a

dc_down:
	${DOCKER_COMPOSE} down --remove-orphans

dc_kill:
	${DOCKER_COMPOSE} kill

db:
	${DOCKER_COMPOSE_DATABASE_EXEC} /bin/sh

cs_check:
	php vendor/bin/php-cs-fixer fix --dry-run

cs_fix:
	php vendor/bin/php-cs-fixer fix

phpstan:
	php vendor/bin/phpstan analyse -c phpstan.neon

deptrac:
	vendor/bin/deptrac analyse

openapi_doc:
	php bin/console nelmio:apidoc:dump --format=yaml > doc/openapi.yaml

init_test_db:
	php bin/console doctrine:database:create --env=test && php bin/console --env=test doctrine:schema:create

drop_test_db:
	php bin/console doctrine:database:drop --force --env=test

tests_unit:
	php vendor/bin/phpunit tests/Unit

tests_integration:
	php vendor/bin/phpunit tests/Integration

tests_functional:
	php vendor/bin/phpunit tests/Functional

migration:
	php bin/console doctrine:migrations:diff

migrate:
	php bin/console doctrine:migrations:migrate