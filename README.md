# elasticsearcher

This agnostic package is a lightweight wrapper around the [elasticsearch PHP API](http://www.elasticsearch.org/guide/en/elasticsearch/client/php-api/current/index.html).
It allows for easier query building and index management. It still gives access to the elasticsearch PHP client, for more
advanced usage.

# Installation

Add this to your `composer.json`:

```
"madewithlove/elasticsearcher": "dev-master"
```

# Usage

```
use ElasticSearcher\Environment;
use ElasticSearcher\ElasticSearcher;

$env = new Environment(
  ['hosts' => ['localhost:9200']]
);
$searcher = new ElasticSearcher($env);
```

## Index management

The package includes an indices manager. You are required to register indices that you'll using, whether its for
CRUD on the indices or for quering. The indices need to be registered with a unique reference which can then be
used in CRUD and query actions.

The index manager is accessed via:

```
$manager = $searcher->indicesManager();
```

### Defining an index

A simple [index](http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/_basic_concepts.html#_index) exists
out of a name and one or more types (+mappings). This can be created as:

```
class SuggestionsIndex extends \ElasticSearcher\Abstracts\IndexAbstract
{
  public function getName()
  {
    return 'suggestions';
  }

  public function getTypes()
  {
    return array(
      'books'   => array(
        'properties' => array(
          'id'   => array(
            'type' => 'integer'
          ),
          'name' => array(
            'type' => 'string'
          )
        )
      ),
      'movies' => array(
        'properties' => array(
          'name' => array(
            'type' => 'string'
          )
        )
      )
    );
  }
}
```

This the minimum required for defining an index. If you require more extensive configuration, override the `getBody`
method.

### Index registration

The indices should be registered with the indices manager for further use in the package. Every index needs a
reference, which will be used for accessing the index.

```
$suggestionsIndex  = new SuggestionsIndex();

// Single registration
$searcher->indicesManager()->register('suggestions', $suggestionsIndex);

// Grouped registration
$indices = array(
  'suggestions'  => $suggestionsIndex,
);
$searcher->indicesManager()->registerIndices($indices);

// Other
$searcher->indexManager()->unregister('suggestions');
$searcher->indexManager()->isRegistered('suggestions');
$searcher->indexManager()->registeredIndices();
```

### Index CRUD

```
// Indices that exist in the server, not linked to the registered indices.
$searcher->indicesManager()->indices());

// Other
$searcher->indicesManager()->exists('listings');
$searcher->indicesManager()->existsType('suggestions', 'movies');
$searcher->indicesManager()->create('suggestions');
$searcher->indicesManager()->update('suggestions');
$searcher->indicesManager()->delete('suggestions');
$searcher->indicesManager()->deleteType('suggestions', 'movies');
```

### Document management

The document manager allows easier CRUD to the documents. The document manager is accessed via:

```
$manager = $searcher->documentsManager();

$data = [
	'id'   => 123
	'name' => 'Fight club'
];
$manager->index('suggestions', 'movies', $data);
$manager->update('suggestions', 'movies', 123, ['name' => 'Fight Club 2014']);
$manager->delete('suggestions', 'movies', 123);
```

## Query building

todo

## Accessing the client

You can access the client instance from the elasticsearch PHP API by using:

```
$client = $searcher->getClient();
```

If for some reason you want you use your own client instance, you can overwrite the one created
by the package:

```
$searcher->setClient($client);
```
