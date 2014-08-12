#!/bin/bash

APPROOT=`dirname $0`;
APPROOT=`cd $APPROOT ; cd ../ ; pwd`;
cd $APPROOT;

echo ">>> Setting up initial database structures...";
bin/doctrine-module orm:schema-tool:drop --force
bin/doctrine-module orm:schema-tool:create
bin/doctrine-module data-fixture:import

bin/app_update.sh
