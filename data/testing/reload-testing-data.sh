#!/bin/bash

DN=`dirname $0`;
SELF=`cd $DN; pwd`;
BASE=`cd $DN; cd ../../; pwd`;

$BASE/bin/doctrine-module orm:schema-tool:drop --force &&
$BASE/bin/doctrine-module orm:schema-tool:create && 
$BASE/bin/doctrine-module data-fixture:import && 
echo -e "\nEnter MySQL Root Password: " && 
mysql -u root -p usarugbystats < $SELF/testing-data.sql
