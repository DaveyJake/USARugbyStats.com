#!/bin/bash

sudo apt-get install -y --force-yes hhvm-fastcgi

# configure apache virtual hosts
sudo cp -f .travisci/hhvm/apache22_vhost.txt /etc/apache2/sites-available/default
sudo sed -e "s?%TRAVIS_BUILD_DIR%?$(pwd)?g" --in-place /etc/apache2/sites-available/default
sudo service hhvm restart
sudo service apache2 restart

