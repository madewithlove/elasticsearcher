# Document management

The document manager allows easier CRUD to the documents. The document manager is accessed via:

```php
$manager = $searcher->documentsManager();

$data = [
	'id'   => 123
	'name' => 'Fight club'
];
$manager->index('suggestions', 'movies', $data);
$manager->update('suggestions', 'movies', 123, ['name' => 'Fight Club 2014']);
$manager->delete('suggestions', 'movies', 123);
$manager->exists('suggestions', 'movies', 123);
$manager->get('suggestions', 'movies', 123);
```
