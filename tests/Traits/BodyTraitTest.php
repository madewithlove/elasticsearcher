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

	public function testGet()
	{
		$mock = $this->getMockForTrait(BodyTrait::class, [], '', true, true, true, ['getBody']);
		$mock->expects($this->once())
			->method('getBody')
			->willReturn(['key' => 'value']);

		$this->assertSame('value', $mock->get('key'));
	}
}
