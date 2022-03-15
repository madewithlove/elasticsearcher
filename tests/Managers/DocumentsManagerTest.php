<?php

use ElasticSearcher\Managers\DocumentsManager;
use ElasticSearcher\Dummy\Indexes\MoviesIndex;
use ElasticSearcher\Dummy\Indexes\BooksIndex;

class DocumentsManagerTest extends ElasticSearcherTestCase
{
	/**
	 * @var DocumentsManager
	 */
	private $documentsManager;

	protected function setUp(): void
	{
		parent::setUp();

		// Create our example index.
		$indicesManager = $this->getElasticSearcher()->indicesManager();
		$indicesManager->register(new MoviesIndex());
		$indicesManager->register(new BooksIndex());

		$this->documentsManager = $this->getElasticSearcher()->documentsManager();
	}

	public function testIndex()
	{
		$id = 11111;
		$data = [
			'id' => $id,
			'name' => 'The Guardians of the Galaxy',
			'year' => 2015,
		];

		// Make sure the document doesn't exist.
		if ($this->documentsManager->exists('movies', 'movies', $id)) {
			$this->documentsManager->delete('movies', 'movies', $id);
		}

		$this->documentsManager->index('movies', 'movies', $data);
		$this->assertTrue($this->documentsManager->exists('movies', 'movies', $id));
		$this->assertEquals($data, $this->documentsManager->get('movies', 'movies', $id)['_source']);
	}

	public function testBulkIndex()
	{
		$documents = [
			[
				'id' => 111112,
				'name' => 'The Guardians of the Galaxy',
				'year' => 2015,
			],
			[
				'id' => 111113,
				'name' => 'Spiderman 4D',
				'year' => 2015,
			],
			[
				'id' => 111114,
				'name' => 'Tokyo Drift',
				'year' => 2013,
			],
		];

		$this->documentsManager->bulkIndex('movies', 'movies', $documents);

		foreach ($documents as $document) {
			$this->assertTrue($this->documentsManager->exists('movies', 'movies', $document['id']));
			$this->assertEquals($document, $this->documentsManager->get('movies', 'movies', $document['id'])['_source']);
		}
	}

	public function testUpdate()
	{
		// Make sure a document exists.
		$id = 33333;
		$data = [
			'id' => $id,
			'name' => 'The Guardians of the Galaxy',
			'year' => 1915,
		];
		$this->documentsManager->updateOrIndex('movies', 'movies', $id, $data);

		$this->documentsManager->update('movies', 'movies', $id, ['year' => 2015]);
		$this->assertEquals(2015, $this->documentsManager->get('movies', 'movies', $id)['_source']['year']);
	}

	public function testDelete()
	{
		// Make sure a document exists.
		$id = 22222;
		$data = [
			'id' => $id,
			'name' => 'The Guardians of the Galaxy',
			'year' => 2015,
		];
		$this->documentsManager->updateOrIndex('movies', 'movies', $id, $data);

		$this->documentsManager->delete('movies', 'movies', $id);
		$this->assertFalse($this->documentsManager->exists('movies', 'movies', $id));
	}

	public function testUpdateOrIndex()
	{
		$id = 12345;

		// Make sure the document doesn't exist.
		if ($this->documentsManager->exists('movies', 'movies', $id)) {
			$this->documentsManager->delete('movies', 'movies', $id);
		}

		$this->documentsManager->updateOrIndex('movies', 'movies', $id, [
			'id' => $id,
			'name' => 'Jurasic World',
			'year' => 2014,
		]);
		$this->assertTrue($this->documentsManager->exists('movies', 'movies', $id));

		$this->documentsManager->updateOrIndex('movies', 'movies', $id, [
			'id' => $id,
			'name' => 'Jurasic World',
			'year' => 2015,
		]);
		$document = $this->documentsManager->get('movies', 'movies', $id);
		$this->assertEquals('2015', $document['_source']['year']);
	}

	public function testWithPrefixedIndex()
	{
		$id = 12345;
		$data = [
			'id' => $id,
			'name' => 'Harry Potter and the wizards',
		];

		// Make sure the document doesn't exist.
		if ($this->documentsManager->exists('books', 'books', $id)) {
			$this->documentsManager->delete('books', 'books', $id);
		}

		$this->documentsManager->index('books', 'books', $data);
		$this->assertTrue($this->documentsManager->exists('books', 'books', $id));
		$this->assertEquals($data, $this->documentsManager->get('books', 'books', $id)['_source']);
	}
}
