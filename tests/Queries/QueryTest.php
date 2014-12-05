<?php

class QueryTest extends ElasticSearcherTestCase
{
	public function setUp()
	{
		parent::setUp();

		// Create our example index.
		$indicesManager = $this->getElasticSearcher()->indicesManager();
		$indicesManager->register(new MoviesIndex());
		if ($indicesManager->exists('movies')) {
			$indicesManager->delete('movies');
		}
		$indicesManager->create('movies');

		// Index some test data.
		$documentsManager = $this->getElasticSearcher()->documentsManager();
		$documentsManager->bulkIndex('movies', 'movies',
			[
				['id' => 1, 'name' => 'Fury', 'year' => 2014],
				['id' => 2, 'name' => 'Interstellar', 'year' => 2014],
				['id' => 3, 'name' => 'Hercules', 'year' => 2014]
			]
		);
	}

	public function testData()
	{
		$query = new MoviesFrom2014Query($this->getElasticSearcher());
		$data  = ['year' => 2014, 'type' => 'dvd'];
		$query->addData($data);

		$this->assertEquals($data, $query->getData());
		$this->assertEquals(2014, $query->getData('year'));
	}

	public function testQueryBuilding()
	{
		$query = new MoviesFrom2014Query($this->getElasticSearcher());
		$query->run(); // Needed because this calls setUp inside the query.

		$this->assertEquals(['movies'], $query->getIndices());
		$this->assertEquals(['movies'], $query->getTypes());

		$expectedQuery = [
			'index' => 'movies',
			'type'  => 'movies',
			'body'  => [
				'query' => [
					'filtered' => [
						'filter' => [
							['term' => ['year' => 2014]]
						]
					]
				]
			]
		];
		$this->assertEquals($expectedQuery, $query->getRawQuery());
	}

	public function testQueryBuildingWithData()
	{
		$query = new MoviesFromXYearQuery($this->getElasticSearcher());
		$query->addData(['year' => 2013]);
		$query->run(); // Needed because this calls setUp inside the query.

		$expectedQuery = [
			'index' => 'movies',
			'type'  => 'movies',
			'body'  => [
				'query' => [
					'filtered' => [
						'filter' => [
							['term' => ['year' => 2013]]
						]
					]
				]
			]
		];
		$this->assertEquals($expectedQuery, $query->getRawQuery());
	}
}
