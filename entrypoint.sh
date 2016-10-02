#!/bin/bash
set -e

install() {
    rm -Rf app/cache/*
    rm -Rf node_modules/
    npm install
    gulp
    composer install
    php app/console assets:install
}

tests() {
    php bin/phpunit -c app/
}

run() {
    chmod 777 * -Rf
    supervisord
}

case "$1" in
"install")
    echo "Install"
    install
    ;;
"tests")
    echo "Tests"
    tests
    ;;
"run")
    echo "Run"
    run
    ;;
*)
    echo "Custom command : $@"
    exec "$@"
    ;;
esac