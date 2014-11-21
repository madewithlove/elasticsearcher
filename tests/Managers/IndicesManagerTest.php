<?php

use ElasticSearcher\Managers\IndicesManager;

class IndicesManagerTest extends PHPUnit_Framework_TestCase
{
	public function testRegister()
	{
		$environment     = $this->getMock('ElasticSearcher\Environment', null, [['hosts' => ['non-working-test:9200']]]);
		$elasticSearcher = $this->getMock('ElasticSearcher\ElasticSearcher', null, [$environment]);

		$manager = new IndicesManager($elasticSearcher);

		$moviesIndex = $this->getMockForAbstractClass('ElasticSearcher\Abstracts\IndexAbstract');
		$moviesIndex
			->expects($this->any())
			->method('getName')
			->willReturn('movies');
		$moviesIndex
			->expects($this->any())
			->method('getTypes')
			->willReturn([]);
		$manager->register($moviesIndex);

		$this->assertEquals(true, $manager->isRegistered('movies'));
		$this->assertEquals(true, $manager->isRegistered('movies'));
	}
}
