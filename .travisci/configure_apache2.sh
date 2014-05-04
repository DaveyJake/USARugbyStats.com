#!/bin/bash

sudo apt-get install apache2 libapache2-mod-fastcgi 
sudo a2enmod rewrite actions fastcgi alias

[ "$(phpenv version-name)" = "hhvm" ]  && .travisci/hhvm/configure.sh
[ "$(phpenv version-name)" != "hhvm" ] && .travisci/php/configure.sh

exit 0

