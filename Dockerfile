FROM anthonykgross/docker-fullstack-web:php5

MAINTAINER Anthony K GROSS

WORKDIR /src

ARG APPLICATION_ENV='dev'
ARG MAILER_USER='MAILER_USER'
ARG MAILER_PASSWORD='MAILER_PASSWORD'
ENV APPLICATION_ENV $APPLICATION_ENV

RUN apt-get update -y && \
	apt-get upgrade -y && \
	apt-get install -y supervisor nginx && \
    rm -rf /var/lib/apt/lists/* && \
    apt-get autoremove -y --purge
    
RUN rm -Rf /etc/php5/* && \
    rm -Rf /etc/supervisor/conf.d/* && \
    rm -Rf /etc/nginx/* && \
    rm -Rf /src/* && \
    rm -Rf /logs/*

COPY entrypoint.sh /entrypoint.sh
COPY conf/php5 /etc/php5
COPY conf/supervisor /etc/supervisor/conf.d
COPY conf/nginx /etc/nginx
COPY src /src
COPY logs /logs

RUN if [ "$APPLICATION_ENV" = "prod" ]; then \
        cp -f /src/app/config/parameters.yml.prod /src/app/config/parameters.yml && \
        sed -i -e "s,\${{MAILER_USER}},$MAILER_USER,g" /src/app/config/parameters.yml && \
        sed -i -e "s,\${{MAILER_PASSWORD}},$MAILER_PASSWORD,g" /src/app/config/parameters.yml \
    ; fi

RUN chmod +x /entrypoint.sh && \
    bash --rcfile "/root/.bash_profile" -ic "/entrypoint.sh permission" && \
    bash --rcfile "/root/.bash_profile" -ic "/entrypoint.sh install" && \
    rm web/app_dev.php

EXPOSE 80
EXPOSE 443

ENTRYPOINT ["/entrypoint.sh"]
CMD ["run"]

