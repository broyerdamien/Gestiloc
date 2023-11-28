include .env

sitename = ${SITEURL_BASE}
DOCKER_COMPOSE = -f docker-compose.yml -p ${sitename}

.PHONY: clean restart start stop run bash clear-cache composer-install install-assets

start:
	docker-compose -p $(sitename) start
	docker ps

stop:
	docker-compose -p $(sitename) stop
	docker ps

restart:
	docker-compose -p $(sitename) restart
	docker ps

build: clean
	docker-compose -p $(sitename) build
	docker-compose -p $(sitename) up -d --remove-orphans
	docker ps

install: build

clean: stop
	docker-compose -p $(sitename) rm -f
	docker ps

logs:
	docker-compose -p $(sitename) logs -f

bash:
	docker exec -it ${sitename}_php_1 /bin/bash

sql_bash:
	docker exec -it ${sitename}_mariadb_1 /bin/bash

setup-cache-rights:
	docker-compose ${DOCKER_COMPOSE} exec -it php /bin/chmod 777 -R app/cache app/logs

update-bdd:
	docker-compose ${DOCKER_COMPOSE} exec -it php app/console doctrine:schema:update --force

clear-cache:
	docker-compose ${DOCKER_COMPOSE} exec -it php app/console ca:cl
	make setup-cache-rights

composer-install:
	docker-compose ${DOCKER_COMPOSE} exec php composer install

install-assets:
	docker-compose ${DOCKER_COMPOSE} exec php app/console assets:install --symlink