# elasticsearcher

This agnostic package is a lightweight wrapper around the [elasticsearch PHP API](http://www.elasticsearch.org/guide/en/elasticsearch/client/php-api/current/index.html).
It allows for easier query building and index management. It still gives access to the elasticsearch PHP client, for more
advanced usage.

# Installation

Installation of the latest version is easy via [composer](https://getcomposer.org/):

```
composer require madewithlove/elasticsearcher
```

# Usage

```php
use ElasticSearcher\Environment;
use ElasticSearcher\ElasticSearcher;

$env = new Environment(
  ['hosts' => ['localhost:9200']]
);
$searcher = new ElasticSearcher($env);
```

# Documentation

* [Index management](./docs/index-management.md)
* [Document management](./docs/document-management.md)
* [Query building (search)](./docs/query-building.md)
* [Result parsing (after search)](./docs/result-parsing.md)

## Accessing the client

You can access the client instance from the elasticsearch PHP API by using:

```php
$client = $searcher->getClient();
```

If for some reason you want you use your own client instance, you can overwrite the one created
by the package:

```php
$searcher->setClient($client);
```
