<?php

class IndicesManagerTest extends ElasticSearcherTestCase
{
	public function testRegister()
	{
		$indicesManager = $this->getElasticSearcher()->indicesManager();

		$moviesIndex = new MoviesIndex();
		$indicesManager->register($moviesIndex);

		$this->assertEquals(true, $indicesManager->isRegistered('movies'));
	}
}
