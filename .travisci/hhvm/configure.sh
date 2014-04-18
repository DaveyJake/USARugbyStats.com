#!/bin/bash

# Start HHVM in FastCGI daemon mode
hhvm --mode daemon -vServer.Type=fastcgi -vServer.Port=9000

# configure apache virtual hosts
sudo cp -f .travisci/hhvm/apache22_vhost.txt /etc/apache2/sites-available/default
sudo sed -e "s?%TRAVIS_BUILD_DIR%?$(pwd)?g" --in-place /etc/apache2/sites-available/default
sudo service apache2 restart

