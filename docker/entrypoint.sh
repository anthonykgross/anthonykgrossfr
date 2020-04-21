#!/bin/bash
set -e

install() {
  permission
  gosu docker yarn
  gosu docker gulp
  gosu docker composer install
  gosu docker php bin/console cache:warmup
}

tests() {
  install
  gosu docker php bin/phpunit
}

run() {
  permission
  supervisord
}

permission() {
  find . \! -user docker -exec chown docker '{}' +
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