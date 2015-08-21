<?php

class AbstractFragmentTest extends ElasticSearcherTestCase
{
	public function testSet()
	{
		$stub = $this->getMockForAbstractClass(ElasticSearcher\Abstracts\AbstractFragment::class);

		$stub->set('key', 'value');
		$this->assertEquals(['key' => 'value'], $stub->getBody());

		$stub->set('key.nested', 'value');
		$this->assertEquals(['key' => ['nested' => 'value']], $stub->getBody());
	}
}
