<?php

class QueryTest extends ElasticSearcherTestCase
{
	public function testData()
	{
		$query = new MoviesFrom2014Query($this->getElasticSearcher());
		$data = ['year' => 2014, 'type' => 'dvd'];
		$query->addData($data);

		$this->assertEquals($data, $query->getData());
		$this->assertEquals(2014, $query->getData('year'));
	}
}
