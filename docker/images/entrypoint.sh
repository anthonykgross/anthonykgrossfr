#!/bin/bash
set -e

source ~/.bash_profile

install() {
    gosu docker yarn
    gosu docker gulp
    gosu docker composer install
    gosu docker php bin/console cache:warmup
    gosu docker php bin/console app:cloudflare:clear-cache
}

tests() {
    gosu docker php bin/phpunit
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