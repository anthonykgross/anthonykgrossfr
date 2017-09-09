NAME=r.cfcr.io/anthonykgross/anthonykgross/anthonykgrossfr

build:
	docker build --file="Dockerfile" --tag="$(NAME):master" .

debug:
	docker-compose run anthonykgrossfr bash

install:
	docker-compose run anthonykgrossfr install

run:
	docker-compose up
