# Index Management

The package includes an indices manager. You are required to register indices that you'll using, whether its for
CRUD on the indices or for quering. The index manager is accessed via:

```php
$manager = $searcher->indicesManager();
```

## Defining an index

A simple [index](http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/_basic_concepts.html#_index) exists
out of a name and one or more types (+mappings). This can be created as:

```php
class SuggestionsIndex extends \ElasticSearcher\Abstracts\AbstractIndex
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
$indices = array(
  $suggestionsIndex
);
$searcher->indicesManager()->registerIndices($indices);

// Other
$searcher->indexManager()->unregister('suggestions');
$searcher->indexManager()->isRegistered('suggestions');
$searcher->indexManager()->registeredIndices();
```

## Index CRUD

```php
// Indices that exist in the server, not linked to the registered indices.
$searcher->indicesManager()->indices());
$searcher->indicesManager()->get('suggestions'));
$searcher->indicesManager()->getType('suggestions', 'books'));

// Other
$searcher->indicesManager()->exists('listings');
$searcher->indicesManager()->existsType('suggestions', 'movies');
$searcher->indicesManager()->create('suggestions');
$searcher->indicesManager()->update('suggestions');
$searcher->indicesManager()->delete('suggestions');
$searcher->indicesManager()->deleteType('suggestions', 'movies');
```
