<?php

use ElasticSearcher\Filters\TermsFilter;

class TermsFilterTest extends PHPUnit_Framework_TestCase
{
	public function testBody()
	{
		$term = new TermsFilter('name', ['elasticsearch', 'github']);

		$this->assertEquals(['terms' => ['name' => ['elasticsearch', 'github']]], $term->getBody());
	}
}
