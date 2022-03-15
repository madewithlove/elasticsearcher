<?php

use ElasticSearcher\Fragments\Queries\TermQuery;
use PHPUnit\Framework\TestCase;

class TermQueryTest extends TestCase
{
	public function testBody()
	{
		$term = new TermQuery('name', 'elasticsearch');

		$this->assertEquals(['term' => ['name' => 'elasticsearch']], $term->getBody());
	}
}
