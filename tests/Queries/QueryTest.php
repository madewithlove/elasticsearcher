<?php

use ElasticSearcher\Dummy\Indexes\BooksIndex;
use ElasticSearcher\Dummy\Indexes\MoviesIndex;
use ElasticSearcher\Dummy\Queries\MoviesFrom2014Query;
use ElasticSearcher\Dummy\Queries\MoviesFromXYearQuery;
use ElasticSearcher\Dummy\Queries\MovieWithIDXQuery;
use ElasticSearcher\Dummy\Queries\CountMoviesFrom2014Query;
use ElasticSearcher\Dummy\Queries\BooksFrom2014Query;

class QueryTest extends ElasticSearcherTestCase
{
	public function setUp()
	{
		parent::setUp();

		// Create our example index.
		$indicesManager = $this->getElasticSearcher()->indicesManager();
		$indicesManager->register(new MoviesIndex());
		$indicesManager->register(new BooksIndex());
		if ($indicesManager->exists('movies')) {
			$indicesManager->delete('movies');
		}
		$indicesManager->create('movies');
		if ($indicesManager->exists('books')) {
			$indicesManager->delete('books');
		}
		$indicesManager->create('books');

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
		$data = ['year' => 2014, 'type' => 'dvd'];
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
			'type' => 'movies',
			'body' => [
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
			'type' => 'movies',
			'body' => [
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

	public function testQueryBuildingWithNestedFragments()
	{
		$query = new MovieWithIDXQuery($this->getElasticSearcher());
		$query->addData(['id' => 1]);
		$query->run(); // Needed because this calls setUp inside the query.

		$expectedQuery = [
			'index' => 'movies',
			'type' => 'movies',
			'body' => [
				'query' => [
					'filtered' => [
						'filter' => [
							['term' => ['id' => 1]]
						]
					]
				]
			]
		];
		$this->assertEquals($expectedQuery, $query->getRawQuery());
	}

	public function testSettingIndicesAndTypes()
	{
		$query = $this->getMockForAbstractClass('\ElasticSearcher\Abstracts\AbstractQuery', [$this->getElasticSearcher()]);

		$query->searchIn('movies');
		$this->assertEquals(['movies'], $query->getIndices());
		$this->assertEquals([], $query->getTypes());

		$query->searchIn('movies', 'authors');
		$this->assertEquals(['movies'], $query->getIndices());
		$this->assertEquals(['authors'], $query->getTypes());

		$query->searchIn(['movies'], ['authors', 'directors']);
		$this->assertEquals(['movies'], $query->getIndices());
		$this->assertEquals(['authors', 'directors'], $query->getTypes());
	}

	public function testSettingSearchType()
	{
		$query = new CountMoviesFrom2014Query($this->getElasticSearcher());

		$raw = $query->getRawQuery();

		$this->assertArrayHasKey('search_type', $raw);
		$this->assertEquals('count', $raw['search_type']);
	}

	public function testQueryBuildingWithPrefixedIndex()
	{
		$query = new BooksFrom2014Query($this->getElasticSearcher());
		$query->run(); // Needed because this calls setUp inside the query.

		$this->assertEquals(['prefix_books'], $query->getIndices());
		$this->assertEquals(['books'], $query->getTypes());

		$expectedQuery = [
			'index' => 'prefix_books',
			'type' => 'books',
			'body' => [
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
}
