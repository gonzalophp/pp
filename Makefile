DOCKER_IMAGE=gonzalophp/pp

build:
	docker build --compress -t ${DOCKER_IMAGE} -f docker/Dockerfile .

stop:
	docker stop pp ||:
	docker wait pp ||:

remove: stop
	docker rm pp ||:

start: remove
	# prod
	# docker run -e APP_ENV=prod -e APP_DEBUG=0 -p 15000:80 -d --name pp ${DOCKER_IMAGE} 
	# dev
	docker run --restart unless-stopped -e APP_ENV=dev -e APP_DEBUG=1 -p 15000:80 -d --name pp ${DOCKER_IMAGE} 

exec:
	docker exec -it pp bash

rsync:
	rsync -e 'docker exec -i' -av --exclude .git --exclude nbproject --exclude vendor --exclude var . pp:/var/www/pension/
	# docker exec pp php bin/console cache:clear

vendor:
	docker cp pp:/var/www/pension/vendor .	

tests:
	vendor/bin/phpunit -c tests/phpunit.xml tests

.PHONY: tests
