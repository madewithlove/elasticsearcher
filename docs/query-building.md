# Query building

You can build a query by extending the `ElasticSearcher\Abstracts\AbstractQuery` class, which will provide some tools to
make it easier to build a query. You are not required to use the tools, you can still set a "raw" query using arrays
like the ElasticSearch SDK offers.

## Minimum query

This basic example will return all documents in the `movies` type in the `suggestions` index. The type is optional, which
would result in all documents inside the `suggestions` index would be returned.

```php
use ElasticSearcher\Abstracts\AbstractQuery;

class MoviesYouMightLikeQuery extends AbstractQuery
{
  public function setup()
  {
    $this->searchIn('suggestions', 'movies');
  }
}
```

Usage:

```php
$query = new MoviesYouMightLikeQuery($searcher);
$result = $query->run();
var_dump($result->getResults());
```

## Body building

Inside your Query class you can build the body of the query however you like. A query is
[body aware](https://github.com/madewithlove/elasticsearcher/tree/master/src/Traits/BodyTrait.php)
for easy manipulation. Here are some examples.

Basic example:

```php
use ElasticSearcher\Abstracts\QueryAbstract;

class MoviesYouMightLikeQuery extends QueryAbstract
{
  public function setup()
  {
    $this->searchIn('suggestions', 'movies');

    // Long notation
    $body = [
      'query' => [
        'bool' => [
          'filter' => [
            'term' => ['status' => 'active']
          ]
        ]
      ]
    ];
    $this->setBody($body);

    // Short dotted notation
    $this->set('query.bool.filter.term.status', 'active');
  }
}
```

Using external data to manipulate your query:

```php
use ElasticSearcher\Abstracts\AbstractQuery;

class MoviesYouMightLikeQuery extends AbstractQuery
{
  public function setup()
  {
    $this->searchIn('suggestions', 'movies');

    $this->set('query.bool.filter.term.status', $this->getData('status'));
  }
}
```

Usage:

```php
$query = new MoviesYouMightLikeQuery($searcher);
$query->addData(['status' => 'active']);
$result = $query->run();
var_dump($result->getResults());
```

## Using re-usable fragments

You can abstract parts of your query to separate classes and re-use them. More about it in the [re-useable fragments](re-useable-fragments.md)
documentation.
