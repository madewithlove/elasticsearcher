<?php

class IndicesManagerTest extends ElasticSearcherTestCase
{
	/**
	 * @var IndicesManager
	 */
	private $indicesManager;

	public function setUp()
	{
		parent::setUp();
		$this->indicesManager = $this->getElasticSearcher()->indicesManager();
	}

	public function testRegister()
	{
		$moviesIndex = new MoviesIndex();

		// Single registration.
		$this->indicesManager->register($moviesIndex);
		$this->assertEquals(true, $this->indicesManager->isRegistered('movies'));
		$this->assertArrayHasKey('movies', $this->indicesManager->registeredIndices());
		$this->assertInstanceOf(MoviesIndex::class, $this->indicesManager->getRegistered('movies'));

		// Removing from register.
		$this->indicesManager->unregister('movies');
		$this->assertEquals(false, $this->indicesManager->isRegistered('movies'));
		$this->assertArrayNotHasKey('movies', $this->indicesManager->registeredIndices());

		// Bulk registering.
		$this->indicesManager->registerIndices([$moviesIndex]);
		$this->assertEquals(true, $this->indicesManager->isRegistered('movies'));
	}
}
