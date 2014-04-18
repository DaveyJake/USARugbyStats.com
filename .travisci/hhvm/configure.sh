#!/bin/bash

# configure apache virtual hosts
sudo cp -f .travisci/hhvm/apache22_vhost.txt /etc/apache2/sites-available/default
sudo sed -e "s?%TRAVIS_BUILD_DIR%?$(pwd)?g" --in-place /etc/apache2/sites-available/default

# restart everything
sudo service hhvm stop 
sudo service apache2 restart

# run HHVM in FastCGI mode
hhvm --mode server -vServer.Type=fastcgi -vServer.Port=9000
