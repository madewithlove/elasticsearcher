<?php

use ElasticSearcher\Traits\BodyTrait;

class BodyTraitTest extends ElasticSearcherTestCase
{
	public function testSet()
	{
		$mock = $this->getMockForTrait(BodyTrait::class);

		$mock->set('key', 'value');
		$this->assertEquals(['key' => 'value'], $mock->getBody());

		$mock->set('key.nested', 'value');
		$this->assertEquals(['key' => ['nested' => 'value']], $mock->getBody());
	}
}
