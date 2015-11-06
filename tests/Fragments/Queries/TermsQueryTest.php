<?php

use ElasticSearcher\Fragments\Queries\TermsQuery;

class TermsQueryTest extends PHPUnit_Framework_TestCase
{
	public function testBody()
	{
		$term = new TermsQuery('name', ['elasticsearch', 'github']);

		$this->assertEquals(['terms' => ['name' => ['elasticsearch', 'github']]], $term->getBody());
	}
}
