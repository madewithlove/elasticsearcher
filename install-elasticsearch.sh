#!/usr/bin/env bash

echo ">>> Installing Elasticsearch"

# Set some variables
ELASTICSEARCH_VERSION=5.1.2 # Check http://www.elasticsearch.org/download/ for latest version

# Install prerequisite: Java8
echo oracle-java8-installer shared/accepted-oracle-license-v1-1 select true | sudo /usr/bin/debconf-set-selections
sudo add-apt-repository ppa:webupd8team/java -y
sudo apt-get update
sudo apt-get install oracle-java8-installer -qq
sudo apt-get install oracle-java8-set-default -qq

wget --quiet https://artifacts.elastic.co/downloads/elasticsearch/elasticsearch-$ELASTICSEARCH_VERSION.deb
sudo dpkg -i elasticsearch-$ELASTICSEARCH_VERSION.deb
rm elasticsearch-$ELASTICSEARCH_VERSION.deb

# Configure Elasticsearch for development purposes (1 shard/no replicas, don't allow it to swap at all if it can run without swapping)
sudo sed -i "s/#index.number_of_shards: 1/index.number_of_shards: 1/" /etc/elasticsearch/elasticsearch.yml
sudo sed -i "s/#index.number_of_replicas: 0/index.number_of_replicas: 0/" /etc/elasticsearch/elasticsearch.yml
sudo sed -i "s/#bootstrap.mlockall: true/bootstrap.mlockall: true/" /etc/elasticsearch/elasticsearch.yml
if [ "$1" != "testing" ] ; then
	echo ">>> Binding elasticsearch to all network hosts"
	sudo sed -i "s/#network.host: 192.168.0.1/network.host: 0.0.0.0/" /etc/elasticsearch/elasticsearch.yml
fi

sudo service elasticsearch restart

# Configure to start up Elasticsearch automatically
sudo update-rc.d elasticsearch defaults 95 10

until $(curl --output /dev/null --silent --head --fail http://localhost:9200); do
  printf ">>> Waiting for elasticsearch to start on localhost:9200\n"
  sleep 5
done
