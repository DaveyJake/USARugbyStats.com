#!/bin/bash

APPROOT=`dirname $0`;
APPROOT=`cd $APPROOT ; cd ../ ; pwd`;
cd $APPROOT;

echo ">>> Installing dependencies via Composer...";
COMPOSER_PROCESS_TIMEOUT=3600 composer install --dev;

echo ">>> Updating database structure and running migration...";
./bin/doctrine-module orm:clear-cache:metadata
./bin/doctrine-module orm:schema-tool:update --dump-sql --force
./bin/doctrine-module migrations:migrate -n 
