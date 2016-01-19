#!/bin/bash
set -e

rm node_modules/ -Rf
rm app/cache/* -Rf

npm install
gulp 

php composer.phar self-update
php composer.phar install

php app/console assets:install

# php app/console sharingame:map-generator
# php app/console sharingame:sprite-generator

chmod 777 * -Rf

supervisord