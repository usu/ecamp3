.PHONY: setup install run print

setup:
	docker-compose up

install:
	docker-compose run --rm frontend npm i
	docker-compose run --rm print npm i
	docker-compose run --rm composer

docker-build:
	docker-compose build

run:
	docker-compose up -d db phpmyadmin rabbitmq
	docker-compose run -d --name backend  --service-ports --entrypoint "./docker-run.sh" backend 
	docker-compose run -d --name print    --service-ports print npm run dev
	docker-compose run    --name frontend --service-ports frontend npm run serve


run-printer:
	docker-compose up -d worker-print-puppeteer
	docker-compose up -d worker-print-weasy