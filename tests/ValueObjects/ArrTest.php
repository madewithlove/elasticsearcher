<?php

use ElasticSearcher\ValueObjects\Arr;

class ArrTest extends ElasticSearcherTestCase
{
	public function testConstructor()
	{
		$array = new Arr(['level 1' => 'value']);

		$this->assertEquals(['level 1' => 'value'], $array->all());
	}

	public function testRootLevelSet()
	{
		$array = new Arr();

		$array->set('emailaddress', 'my-email-address');

		$this->assertEquals(['emailaddress' => 'my-email-address'], $array->all());
	}

	public function testDottedSet()
	{
		$array = new Arr();

		$array->set('email.address', 'my-email-address');

		$this->assertEquals(['email' => ['address' => 'my-email-address']], $array->all());
	}

	public function testRootLevelGet()
	{
		$array = new Arr(['emailaddress' => 'my-email-address']);

		$this->assertEquals('my-email-address', $array->get('emailaddress'));
	}

	public function testDottedGet()
	{
		$array = new Arr(['email' => ['address' => 'my-email-address']]);

		$this->assertEquals('my-email-address', $array->get('email.address'));
	}
}
