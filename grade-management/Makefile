up:
	docker compose up -d

down:
	docker compose down

build:
	docker compose up -d --build

test:
	docker exec -it grade-management-web ./vendor/bin/phpunit

reset-db:
	docker compose down && sudo rm -rf volumes/mysql_data/* && docker compose up -d
