#!/usr/bin/env sh
set -e

# Make sure that Xvfb starts everytime the box/vm is booted:
echo "Starting X virtual framebuffer (Xvfb) in background..."
Xvfb -ac :99 -screen 0 1280x1024x16 > /tmp/xvfb_output.txt 2>&1 &
export DISPLAY=:99

curl http://selenium-release.storage.googleapis.com/2.41/selenium-server-standalone-2.41.0.jar > /tmp/selenium.jar
java -jar /tmp/selenium.jar > /dev/null 2> /tmp/webdriver_output.txt &

sleep 5
