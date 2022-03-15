# Index Management

The package includes an indices manager. You are required to register indices that you'll using, whether its for
CRUD on the indices or for querying. The index manager is accessed via:

```php
$manager = $searcher->indicesManager();
```

## Defining an index

A simple [index](http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/_basic_concepts.html#_index) exists
out of a name and +mappings. This can be created as:

```php
class SuggestionsIndex extends \ElasticSearcher\Abstracts\AbstractIndex
{
  public function getName()
  {
    return 'suggestions';
  }

  public function setup()
  {
    $this->setMappings([
      'properties' => [
        'id' => ['type' => 'integer'],
        'name' => ['type' => 'text'],
      ]
    ]);
  }
}
```

This the minimum required for defining an index. Inside the `setup()` method you can setup your index further,
for example adding settings, aggregations, .... An index is [body aware](https://github.com/madewithlove/elasticsearcher/tree/master/src/Traits/BodyTrait.php)
for easy manipulation.

### Using re-useable fragments

You can abstract parts of your index to separate classes and re-use them. More about it in the [re-useable fragments](re-useable-fragments.md)
documentation.

## Index registration

The indices should be registered with the indices manager for further use in the package. The index object is only
used during this registration, later on it can accessed by its name (via `getName`).

```php
$suggestionsIndex  = new SuggestionsIndex();

// Single registration
$searcher->indicesManager()->register($suggestionsIndex);

// Grouped registration
$indices = [
  $suggestionsIndex
];
$searcher->indicesManager()->registerIndices($indices);

// Other
$searcher->indicesManager()->unregister('suggestions');
$searcher->indicesManager()->isRegistered('suggestions');
$searcher->indicesManager()->registeredIndices();
```

## Index CRUD

```php
// Indices that exist in the server, not linked to the registered indices.
$searcher->indicesManager()->indices();
$searcher->indicesManager()->get('suggestions');

// Other
$searcher->indicesManager()->exists('listings');
$searcher->indicesManager()->create('suggestions');
$searcher->indicesManager()->update('suggestions');
$searcher->indicesManager()->delete('suggestions');
```
