#!/usr/bin/env bash

echo ">>> Installing PHP7"

sudo add-apt-repository ppa:ondrej/php -y
sudo apt-get update
sudo apt-get install php7.0 -y
sudo apt-get install php7.0-xml -y
sudo apt-get install php7.0-mbstring -y
sudo apt-get install php7.0-curl -y
