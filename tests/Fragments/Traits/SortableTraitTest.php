<?php

use ElasticSearcher\Dummy\Queries\SortedQuery;

class SortableTraitTest extends ElasticSearcherTestCase
{
	public function testSettingSortingFields()
	{
		$this->hasSort(
			[
				'sort_fields' => [
					'name',
					'age' => ['order' => 'asc', 'mode' => 'avg'],
					'date' => 'asc',
				]
			],
			null,
			[
				'name',
				['age' => ['order' => 'asc', 'mode' => 'avg']],
				['date' => 'asc'],
			]
		);
	}

	public function testSortingAFieldWithoutPredefinedFields()
	{
		$this->hasSort(
			null,
			['sort' => 'date', 'sort_direction' => 'desc'],
			[
				['date' => 'desc'],
			]
		);
	}

	public function testSortingAFieldFromPredefinedFields()
	{
		$this->hasSort(
			[
				'sort_fields' => [
					'name' => 'asc',
					'age' => ['order' => 'asc', 'mode' => 'avg'],
					'date' => 'asc',
				]
			],
			['sort' => 'date', 'sort_direction' => 'desc'],
			[
				['date' => 'desc'],
				['name' => 'asc'],
				['age' => ['order' => 'asc', 'mode' => 'avg']],
			]
		);
	}

	public function testSortingAnUndefinedFieldWithPredefinedFields()
	{
		$this->hasSort(
			[
				'sort_fields' => [
					'name' => 'asc',
					'age' => ['order' => 'asc', 'mode' => 'avg'],
					'date' => 'asc',
				]
			],
			['sort' => 'email', 'sort_direction' => 'asc'],
			[
				['email' => 'asc'],
				['name' => 'asc'],
				['age' => ['order' => 'asc', 'mode' => 'avg']],
				['date' => 'asc'],
			]
		);
	}

	public function testSortingAFieldWithoutSortDirection()
	{
		$fields = [
			'sort_fields' => [
				'name',
				'age' => ['order' => 'asc', 'mode' => 'avg'],
				'date' => 'desc',
			]
		];

		$this->hasSort(
			$fields,
			['sort' => 'date', 'sort_direction' => null],
			[
				['date' => 'desc'],
				'name',
				['age' => ['order' => 'asc', 'mode' => 'avg']],
			]
		);
		$this->hasSort(
			$fields,
			['sort' => 'age', 'sort_direction' => null],
			[
				['age' => ['order' => 'asc', 'mode' => 'avg']],
				'name',
				['date' => 'desc'],
			]
		);
	}

	public function testSortingAFieldWithoutPredefinedDirection()
	{
		$fields = [
			'sort_fields' => [
				'name',
				'age' => ['order' => 'asc', 'mode' => 'avg'],
				'date' => 'desc',
			]
		];

		$this->hasSort(
			[
				'sort_fields' => [
					'name',
					'age' => ['order' => 'asc', 'mode' => 'avg'],
					'date' => 'desc',
				]
			],
			['sort' => 'name'],
			[
				'name',
				['age' => ['order' => 'asc', 'mode' => 'avg']],
				['date' => 'desc'],
			]
		);
		$this->hasSort(
			$fields,
			['sort' => 'name', 'sort_direction' => 'desc'],
			[
				['name' => 'desc'],
				['age' => ['order' => 'asc', 'mode' => 'avg']],
				['date' => 'desc'],
			]
		);
	}

	/**
	 * @param null|array $predefinedFields
	 * @param null|array $sortBy
	 * @param array $expectedSort
	 */
	private function hasSort($predefinedFields = null, $sortBy = null, array $expectedSort)
	{
		$query = new SortedQuery($this->getElasticSearcher());
		if ($predefinedFields) {
			$query->addData($predefinedFields);
		}
		if ($sortBy) {
			$query->addData($sortBy);
		}

		$raw = $query->getRawQuery()['body'];

		$this->assertArrayHasKey('sort', $raw);
		$this->assertEquals($raw['sort'], $expectedSort);
	}
}
