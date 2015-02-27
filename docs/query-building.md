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

Inside your Query class you can build the body of the query however you like. We only require you to call `setBody` with
the body array. Here are some examples.

Basic example:

```php
use ElasticSearcher\Abstracts\QueryAbstract;

class MoviesYouMightLikeQuery extends QueryAbstract
{
  public function setup()
  {
    $this->searchIn('suggestions', 'movies');

     $body = array(
      'query' => array(
        'filtered' => array(
          'filter' => array(
            'term' => array(
              'status' => 'active'
            )
          )
        )
      )
     );

     $this->setBody($body);
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

     $body = array(
      'query' => array(
        'filtered' => array(
          'filter' => array(
            'term' => array(
              'status' => $this->getData('status')
            )
          )
        )
      )
     );

     $this->setBody($body);
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

## Using abstract fragments

We have a set of build in abstract fragments (filters, queries, aggregations, ...) that build chunks of the body for you.
If you have body fragements that are repeated in multiple queries, you can create your own project fragments, they only
need to extend the `ElasticSearcher\Abstracts\AbstractFragment` class.

```php
use ElasticSearcher\Abstracts\AbstractQuery;
use ElasticSearcher\Fragments\Filters\TermFilter;

class MoviesYouMightLikeQuery extends AbstractQuery
{
  public function setup()
  {
    $this->searchIn('suggestions', 'movies');

     $body = array(
      'query' => array(
        'filtered' => array(
          'filter' => array(
            new TermFilter('status', 'active')
          )
        )
      )
     );

     $this->setBody($body);
  }
}
```

We encourage you to create your own application specific filters. For example: `BookingRangeFilter(date, date)`, `MovieIDFilter(int)`, ....
As long as they extend `ElasticSearcher\Abstracts\AbstractFragment` they can be used in queries.
