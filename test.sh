#!/bin/bash

# @TODO phing manifest?

for DIR in module config; do bin/php-cs-fixer fix -v --fixers=-psr0,psr4 $DIR; done;

find module -type d -name "tests" | while read DIR; do ./bin/phpunit -c $DIR/phpunit.xml; done;
