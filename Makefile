shell:
	docker exec -it project-manager-php bash
npm-install:
	docker-compose run --rm node yarn install
dep-add:
	docker-compose run --rm node yarn add -s bootstrap jquery popper.js @popperjs/core sass-loader node-sass
npm-dev:
	docker-compose run --rm node npm run  dev
watch:
	docker-compose run --rm node npm run watch

down:
	docker-compose down
build:
	docker-compose build --no-cache
up:
	docker-compose up -d
