#!/usr/bin/env sh
set -e

cp /etc/init.d/xvfb /tmp/xvfb.old;
sed -i 's/1024x768/1600x1200/' /etc/init.d/xvfb

# Make sure that Xvfb starts everytime the box/vm is booted:
echo "Starting X virtual framebuffer (Xvfb) in background..."
export DISPLAY=:99.0
sh -e /etc/init.d/xvfb start

sleep 5

cp /tmp/xvfb.old /etc/init.d/xvfb;

curl http://selenium-release.storage.googleapis.com/2.41/selenium-server-standalone-2.41.0.jar > /tmp/selenium.jar
java -jar /tmp/selenium.jar > /dev/null 2> /tmp/webdriver_output.txt &
SELENIUMPID=$!

sleep 5

if [ "$1" = "interactive" ];
then
    echo -e "\nPress and key to terminate Selenium and Xvfb...";
    read QUIT

    kill -9 $SELENIUMPID
    sh -e /etc/init.d/xvfb stop
fi;
