<?php

use ElasticSearcher\Managers\DocumentsManager;

class DocumentsManagerTest extends ElasticSearcherTestCase
{
	/**
	 * @var DocumentsManager
	 */
	private $documentsManager;

	public function setUp()
	{
		parent::setUp();

		// Create our example index.
		$indicesManager = $this->getElasticSearcher()->indicesManager();
		$indicesManager->register(new MoviesIndex());

		$this->documentsManager = $this->getElasticSearcher()->documentsManager();
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
			'year' => 2014
		]);
		$this->assertTrue($this->documentsManager->exists('movies', 'movies', $id));

		$this->documentsManager->updateOrIndex('movies', 'movies', $id, [
			'id' => $id,
			'name' => 'Jurasic World',
			'year' => 2015
		]);
		$document = $this->documentsManager->get('movies', 'movies', $id);
		$this->assertEquals('2015', $document['_source']['year']);
	}
}
