#!/bin/bash

cd /project;
echo ">>> Installing dependencies via Composer...";
composer install --dev;

echo ">>> Setting up initial database structures...";
php ./bin/doctrine-module orm:schema-tool:create
php ./bin/doctrine-module data-fixture:import
