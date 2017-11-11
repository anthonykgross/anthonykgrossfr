#!/bin/bash
set -e

source ~/.bash_profile

install() {
    gosu docker npm install
    gosu docker gulp
    gosu docker composer install
    gosu docker php bin/console assets:install
}

tests() {
    gosu docker php vendor/bin/phpunit -c app/
}

run() {
    supervisord
}

permission() {
    chown -Rf docker:docker .
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
"permission")
    echo "Permission"
    permission
    ;;
*)
    echo "Custom command : $@"
    exec "$@"
    ;;
esac