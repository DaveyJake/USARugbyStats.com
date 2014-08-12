#!/bin/bash

echo ">>> Copying configuration files...";
cp -R /vagrant/files/* /project/config/autoload;

cd /project;
bash ./bin/app_rebuild.sh;
