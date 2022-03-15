# Document management

The document manager allows easier CRUD to the documents. The document manager is accessed via:

```php
$manager = $searcher->documentsManager();

$data = [
	'id'   => 123
	'name' => 'Fight club'
];
$manager->index('suggestions', $data);
$manager->bulkIndex('suggestions', [$data, $data, $data]);
$manager->update('suggestions', 123, ['name' => 'Fight Club 2014']);
$manager->updateOrIndex('suggestions', 123, ['name' => 'Fight Club 2014']);
$manager->delete('suggestions', 123);
$manager->exists('suggestions', 123);
$manager->get('suggestions', 123);
```
