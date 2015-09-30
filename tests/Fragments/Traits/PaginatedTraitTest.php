<?php

class PaginatedTraitTest extends ElasticSearcherTestCase
{
	public function testPaginating()
	{
		$query = new PaginatedMoviesFrom2014Query($this->getElasticSearcher());

		$raw = $query->getRawQuery()['body'];

		$this->assertArrayHasKey('from', $raw);
		$this->assertArrayHasKey('size', $raw);
		$this->assertEquals('20', $raw['from']);
		$this->assertEquals('10', $raw['size']);
	}
}
