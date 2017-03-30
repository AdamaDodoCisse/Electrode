#!/bin/sh
## Installation de Docker
apt-get update
apt-get install apt-transport-https curl -y --allow-unauthenticated
## Installation du client MySQL
apt-get install -y --allow-unauthenticated mysql-client

echo deb https://get.docker.com/ubuntu docker main >> /etc/apt/sources.list
apt-get update
apt-get install lxc-docker  -y --allow-unauthenticated

## Installation Docker  Compose
curl -L https://github.com/docker/compose/releases/download/1.8.0/docker-compose-`uname -s`-`uname -m` > /usr/loca\
l/bin/docker-compose
chmod +x /usr/local/bin/docker-compose

# Lancement automatique de docker
update-rc.d docker enable
