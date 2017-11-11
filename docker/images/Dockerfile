FROM anthonykgross/docker-fullstack-web:php7

MAINTAINER Anthony K GROSS

WORKDIR /var/www/html

ARG APPLICATION_ENV='dev'
ARG MAILER_USER='MAILER_USER'
ARG MAILER_PASSWORD='MAILER_PASSWORD'
ENV APPLICATION_ENV $APPLICATION_ENV

RUN apt-get update -y && \
	apt-get upgrade -y && \
	apt-get install -y supervisor nginx && \
    rm -rf /var/lib/apt/lists/* && \
    apt-get autoremove -y --purge && \
    rm -Rf /etc/nginx/sites-enabled/default && \
    mkdir -p /var/www/logs && \
    npm install -g uglify-js

ADD docker/images/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

COPY . /var/www/html
COPY ./docker/conf/supervisor.conf /etc/supervisor/conf.d/supervisor.conf
COPY ./docker/conf/nginx.conf /etc/nginx/sites-enabled/nginx.conf

RUN if [ "$APPLICATION_ENV" = "prod" ]; then \
        cp -f app/config/parameters.yml.prod app/config/parameters.yml && \
        sed -i -e "s,\${{MAILER_USER}},$MAILER_USER,g" app/config/parameters.yml && \
        sed -i -e "s,\${{MAILER_PASSWORD}},$MAILER_PASSWORD,g" app/config/parameters.yml \
    ; fi

RUN chmod +x /entrypoint.sh && \
    bash --rcfile "/root/.bash_profile" -ic "/entrypoint.sh permission" && \
    bash --rcfile "/root/.bash_profile" -ic "/entrypoint.sh install" && \
    rm web/app_dev.php

EXPOSE 80
EXPOSE 443

ENTRYPOINT ["/entrypoint.sh"]
CMD ["run"]

