<?php

use ElasticSearcher\Filters\TermFilter;

class TermFilterTest extends PHPUnit_Framework_TestCase
{
	public function testBody()
	{
		$term = new TermFilter('name', 'elasticsearch');

		$this->assertEquals(['term' => ['name' => 'elasticsearch']], $term->getBody());
	}
}
