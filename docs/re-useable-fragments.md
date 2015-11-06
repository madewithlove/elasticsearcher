# Re-useable Fragments

You can abstract parts of a query or index to a separate class. This allows for re-using of these parts. The package
comes with a build-in set of fragments but you are encouraged to build your own.
For example: `BookingRangeFilter(date, date)`, `MovieIDFilter(int)`, .... As long as they extend
`ElasticSearcher\Abstracts\AbstractFragment` they can be used in queries or indices.

A fragment is [body aware](https://github.com/madewithlove/elasticsearcher/tree/master/src/Traits/BodyTrait.php)
for easy manipulation.

## Examples

```php
use ElasticSearcher\Abstracts\AbstractQuery;
use ElasticSearcher\Fragments\Queries\TermQuery;

class MoviesYouMightLikeQuery extends AbstractQuery
{
  public function setup()
  {
    $this->searchIn('suggestions', 'movies');

     $body = array(
      'query' => array(
        'bool' => array(
          'filter' => array(
            new TermQuery('status', 'active')
          )
        )
      )
     );

     $this->setBody($body);
  }
}
```

```php
use ElasticSearcher\Abstracts\AbstractQuery;
use ElasticSearcher\Fragments\Analyzers\StandardAnalyzer;

class AuthorsIndex extends AbstractIndex
{
	public function getName()
	{
		return 'authors';
	}

	public function getBody()
	{
		$body = [
			'settings' => [
				'analysis' => [
					'analyzer' => [
						new StandardAnalyzer('testAnalyzer')
					]
				]
			],
			'mappings' => $this->getTypes()
		];
	}
}
```

## Merge with parent

The default behavior is the replace the fragment with its body. In some cases you want the fragment to be merged with its
root. You can do this by setting the `mergeWithParent` property on a fragment to `true`.

### Default (`mergeWithParent = false`):

```php
$body = [
	'and' => [
		new TermQuery('status', 'active'),
		new TermQuery('published', true),
	]
];

$parsedBody = [
	'and' => [
		['term' => ['status' => 'active']],
		['term' => ['published' => true]],
	]
];
```

### Merge (`mergeWithParent = true`):

```php
$body = [
	'analyzer' => [
		new StandardAnalyzer('myAnalyzer'),
		new StandardAnalyzer('yourAnalyzer'),
	]
];

$parsedBody = [
	'analyzer' => [
		'myAnalyzer' => ['type' => 'standard'],
		'yourAnalyzer' => ['type' => 'standard'],
	]
];
```
