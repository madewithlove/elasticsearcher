<?php

use ElasticSearcher\Fragments\Queries\TermsQuery;
use PHPUnit\Framework\TestCase;

class TermsQueryTest extends TestCase
{
	public function testBody()
	{
		$term = new TermsQuery('name', ['elasticsearch', 'github']);

		$this->assertEquals(['terms' => ['name' => ['elasticsearch', 'github']]], $term->getBody());
	}
}
