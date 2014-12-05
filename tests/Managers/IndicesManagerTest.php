<?php

use ElasticSearcher\Managers\IndicesManager;

class IndicesManagerTest extends ElasticSearcherTestCase
{
	public function testRegister()
	{
		$indicesManager = new IndicesManager($this->getElasticSearcher());
		$moviesIndex    = new MoviesIndex();

		// Single registration.
		$indicesManager->register($moviesIndex);
		$this->assertEquals(true, $indicesManager->isRegistered('movies'));
		$this->assertArrayHasKey('movies', $indicesManager->registeredIndices());
		$this->assertInstanceOf(MoviesIndex::class, $indicesManager->getRegistered('movies'));

		// Removing from register.
		$indicesManager->unregister('movies');
		$this->assertEquals(false, $indicesManager->isRegistered('movies'));
		$this->assertArrayNotHasKey('movies', $indicesManager->registeredIndices());

		// Bulk registering.
		$indicesManager->registerIndices([$moviesIndex]);
		$this->assertEquals(true, $indicesManager->isRegistered('movies'));
	}
}
