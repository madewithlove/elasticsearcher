# Query building

You can build a query by extending the `QueryAbstract` class, which will provide some tools to make it easier to build
a query. You are not required to use the tools, you can still set a "raw" query using arrays like the ElasticSearch SDK
offers.

```php
use ElasticSearcher\Abstracts\QueryAbstract;

class MoviesYouMightLikeQuery extends QueryAbstract
{
	public function setup()
	{
		$this->searchIn('suggestions', 'movies');

		// Build the query.
		$body = array(
			'query' => array(
				'filtered' => array(
					'query' => array(),
					'filter' => array(
						'term' => array(
							'status' => $this->getData('status')
						)
					)
				)
			),
			'sort' => array(
					'released_at' => 'desc'
			)
		);

		$this->setBody($body);
	}
}
```

Usage:

```php
$query = new MoviesYouMightLikeQuery($searcher);
$query->addData(['status' => 'Saving Private']);

$results = $query->getResults();
```
