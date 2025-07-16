DOCKER_IMAGE=gonzalophp/pp

build: remove
	docker build --compress -t ${DOCKER_IMAGE} -f docker/Dockerfile .

stop:
	docker stop pp ||:
	docker wait pp ||:

remove: stop
	docker rm pp ||:

build-react:
	npm run-script build-react

start: remove build-react
	# prod
	# docker run -e APP_ENV=prod -e APP_DEBUG=0 -p 15000:80 -d --name pp ${DOCKER_IMAGE} 
	# dev
	npm install
	docker run --restart unless-stopped -e APP_ENV=dev -e APP_DEBUG=1 -p 15000:80 -d --name pp ${DOCKER_IMAGE} 

exec:
	docker exec -it pp bash

rsync: build-react	
	rsync -e 'docker exec -i' -av --exclude .git --exclude nbproject --exclude vendor --exclude var --exclude node_modules . pp:/var/www/pension/
	# docker exec pp php bin/console cache:clear

vendor:
	docker cp pp:/var/www/pension/vendor .	

tests:
	vendor/bin/phpunit -c tests/phpunit.xml tests

.PHONY: tests
