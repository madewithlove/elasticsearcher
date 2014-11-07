# Result parsing

After executing a query and calling `getResults` you will, by default, get an array with the "hits". You can, and probably
should, customize this for your own needs. If you need more info from the response or you wish to convert the "hits" into
models via your ORM, this is the place to be.

## Creating a parser

Create a class that extends `ResultParserAbstract` and implement `getResults()`. Thats about it.

```php
use ElasticSearcher\Abstracts\ResultParserAbstract;

class MoviesResultParser extends ResultParserAbstract
{
	public function getResults()
	{
		return $this->getHits();
	}
}
```

Inside the ResultParser you have access to a few build in methods and a method to quickly fetch chunks from the
raw results.

```php
$this->getRawResults();
$this->getHits();
$this->getTotal();

// Get chunks using the dot notation.
$this->get('aggregations.size.buckets.key');
```

## Registering the parser

Inside the setup of your Query, you can pass an instance of your custom parser.

```php
use ElasticSearcher\Abstracts\QueryAbstract;

class MoviesYouMightLikeQuery extends QueryAbstract
{
  public function setup()
  {
    $this->searchIn('suggestions', 'movies');

    $this->parseResultsWith(new MoviesResultParser());
  }
}
```
