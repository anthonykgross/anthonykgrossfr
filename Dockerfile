FROM registry.gitlab.com/anthonykgross/akg-php5:master

MAINTAINER Anthony K GROSS

ENV VIRTUAL_HOST "anthonykgross.fr,anthonykgross.local"

WORKDIR /src

RUN apt-get update -y && \
	apt-get upgrade -y && \
	apt-get install -y supervisor nginx && \
    rm -rf /var/lib/apt/lists/* && \
    apt-get autoremove -y --purge && \
    usermod -u 1000 www-data

COPY entrypoint.sh /entrypoint.sh
COPY conf/php5 /etc/php5
COPY conf/supervisor /etc/supervisor/conf.d
COPY conf/nginx /etc/nginx
COPY src /src
COPY logs /logs

RUN chmod 777 /logs -Rf && \
    chmod 777 /src -Rf && \
    chmod +x /entrypoint.sh && \
    sh /entrypoint.sh install && \
    rm web/app_dev.php

EXPOSE 80
EXPOSE 443

ENTRYPOINT ["/entrypoint.sh"]
CMD ["run"]