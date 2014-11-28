<?php

class ElasticSearcherTest extends PHPUnit_Framework_TestCase
{
	public function testGetters()
	{
		$env             = new ElasticSearcher\Environment(['hosts' => ['my-server']]);
		$elasticSearcher = new ElasticSearcher\ElasticSearcher($env);

		$this->assertInstanceOf(Elasticsearch\Client::class, $elasticSearcher->getClient());
		$this->assertInstanceOf(ElasticSearcher\Managers\IndicesManager::class, $elasticSearcher->indicesManager());
		$this->assertInstanceOf(ElasticSearcher\Managers\DocumentsManager::class, $elasticSearcher->documentsManager());
	}
}
