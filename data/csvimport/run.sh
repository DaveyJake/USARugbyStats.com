#!/bin/bash

BASEDIR=`dirname $0`;
HEREDIR=`cd $BASEDIR ; pwd;`;
BASEDIR=`cd $BASEDIR ; cd ../../; pwd;`;

$BASEDIR/bin/doctrine-module orm:schema-tool:drop --force 
$BASEDIR/bin/doctrine-module orm:schema-tool:create 
$BASEDIR/bin/doctrine-module data-fixture:import

$BASEDIR/bin/console data-importer run-task --task=Competition.ImportUnions --file=$HEREDIR/files/unions.csv
$BASEDIR/bin/console data-importer run-task --task=Competition.ImportTeams --file=$HEREDIR/files/teams.csv
$BASEDIR/bin/console data-importer run-task --task=Competition.ImportCompetitions --file=$HEREDIR/files/competitions.csv
$BASEDIR/bin/console data-importer run-task --task=Account.ImportAccounts --file=$HEREDIR/files/accounts.csv
