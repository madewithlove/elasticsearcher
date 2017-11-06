<?php

use ElasticSearcher\Dummy\Queries\PaginatedMoviesFrom2014Query;

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

	public function testPaginatingWithPageZero()
	{
		$query = new PaginatedMoviesFrom2014Query($this->getElasticSearcher());
		$query->addData(['page' => 0]);
		$query->paginate(0, 10);

		$raw = $query->getRawQuery()['body'];

		$this->assertEquals('0', $raw['from']);
		$this->assertEquals('10', $raw['size']);
	}
}
