FROM ubuntu:bionic

WORKDIR /var/www/html
RUN groupadd -r -g 999 docker && useradd -r -g docker -u 999 docker --create-home

# Define timezone
ENV TZ=Europe/Paris
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && \
    echo $TZ > /etc/timezone

RUN apt-get update -y && \
	apt-get upgrade -y && \
	apt-get install -y \
	    ca-certificates \
	    gnupg2 \
	    software-properties-common \
	    wget \
	    curl \
	    git \
	    supervisor \
	    nginx

RUN add-apt-repository ppa:ondrej/php && \
    apt-get update -y && \
	apt-get install -y \
        php7.4-fpm \
	    php7.4-zip \
	    php7.4-mysql \
	    php7.4-pgsql \
	    php7.4-opcache \
	    php7.4-xml \
	    php7.4-mbstring \
	    php7.4-curl \
	    php7.4-bcmath \
    && \
    echo "user = docker" >> /etc/php/7.4/fpm/pool.d/www.conf && \
    service php7.4-fpm start && \
    rm -Rf /etc/nginx/sites-enabled/default && \
    mkdir -p /var/www/logs

# Install Gosu
ENV GOSU_VERSION 1.11
RUN for server in $(shuf -e ha.pool.sks-keyservers.net \
            hkp://p80.pool.sks-keyservers.net:80 \
            keyserver.ubuntu.com \
            hkp://keyserver.ubuntu.com:80 \
            pgp.mit.edu) ; do \
        gpg --keyserver "$server" --recv-keys B42F6819007F00F88E364FD4036A9C25BF357DD4 && break || : ; \
    done && \
    curl -o /usr/local/bin/gosu -SL "https://github.com/tianon/gosu/releases/download/$GOSU_VERSION/gosu-$(dpkg --print-architecture)" && \
    curl -o /usr/local/bin/gosu.asc -SL "https://github.com/tianon/gosu/releases/download/$GOSU_VERSION/gosu-$(dpkg --print-architecture).asc" && \
	gpg --batch --verify /usr/local/bin/gosu.asc /usr/local/bin/gosu && \
    rm /usr/local/bin/gosu.asc && \
    chmod +x /usr/local/bin/gosu

# Install nodejs
RUN curl -sL https://deb.nodesource.com/setup_10.x | bash - && \
	apt-get install -y nodejs && \
	npm install -g uglify-js gulp yarn

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    chmod a+x composer.phar && \
    mv composer.phar /usr/local/bin/composer

# Cleaning
RUN rm -rf /var/lib/apt/lists/* && \
    apt-get autoremove -y --purge

ADD docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

COPY . /var/www/html
COPY ./docker/supervisor.conf /etc/supervisor/conf.d/supervisor.conf
COPY ./docker/nginx.conf /etc/nginx/sites-enabled/nginx.conf

ARG MAILER_USER='MAILER_USER'
ARG MAILER_PASSWORD='MAILER_PASSWORD'

ARG DB_USER='DB_USER'
ARG DB_PASSWORD='DB_PASSWORD'
ARG DB_HOST='DB_HOST'
ARG DB_NAME='DB_NAME'

ARG ALGOLIA_APP_ID='ALGOLIA_APP_ID'
ARG ALGOLIA_ADMIN_KEY='ALGOLIA_ADMIN_KEY'
ARG ALGOLIA_API_KEY='ALGOLIA_API_KEY'

ARG ADMIN_USER='admin'
ARG ADMIN_SHA512_PASSWORD='nhDr7OyKlXQju+Ge/WKGrPQ9lPBSUFfpK+B1xqx/+8zLZqRNX0+5G1zBQklXUFy86lCpkAofsExlXiorUcKSNQ=='

ARG APPLICATION_ENV='dev'
ENV APPLICATION_ENV $APPLICATION_ENV
RUN if [ "$APPLICATION_ENV" = "prod" ]; then \
        cp -f .env.prod .env && \
        sed -i -e "s,\${{MAILER_USER}},$MAILER_USER,g" .env && \
        sed -i -e "s,\${{MAILER_PASSWORD}},$MAILER_PASSWORD,g" .env && \
        \
        sed -i -e "s,\${{DB_USER}},$DB_USER,g" .env && \
        sed -i -e "s,\${{DB_PASSWORD}},$DB_PASSWORD,g" .env && \
        sed -i -e "s,\${{DB_HOST}},$DB_HOST,g" .env && \
        sed -i -e "s,\${{DB_NAME}},$DB_NAME,g" .env && \
        \
        sed -i -e "s,\${{ALGOLIA_APP_ID}},$ALGOLIA_APP_ID,g" .env && \
        sed -i -e "s,\${{ALGOLIA_ADMIN_KEY}},$ALGOLIA_ADMIN_KEY,g" .env && \
        sed -i -e "s,\${{ALGOLIA_API_KEY}},$ALGOLIA_API_KEY,g" .env && \
        \
        sed -i -e "s,admin:,$ADMIN_USER:,g" config/packages/security.yaml && \
        sed -i -e "s,nhDr7OyKlXQju+Ge/WKGrPQ9lPBSUFfpK+B1xqx/+8zLZqRNX0+5G1zBQklXUFy86lCpkAofsExlXiorUcKSNQ==,$ADMIN_SHA512_PASSWORD,g" config/packages/security.yaml && \
        echo "Settings done !" \
    ; fi

RUN entrypoint.sh install

EXPOSE 80
EXPOSE 443

ENTRYPOINT ["entrypoint.sh"]
CMD ["run"]

