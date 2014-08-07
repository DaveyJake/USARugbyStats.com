#!/bin/bash

echo ">>> Copying configuration files...";
cp -R /vagrant/files/* /project/config/autoload;

cd /project;
echo ">>> Installing dependencies via Composer...";
COMPOSER_PROCESS_TIMEOUT=3600 composer install --dev;

echo ">>> Setting up initial database structures...";
php ./bin/doctrine-module orm:schema-tool:create
php ./bin/doctrine-module data-fixture:import
