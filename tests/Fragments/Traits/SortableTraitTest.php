<?php

use ElasticSearcher\Dummy\Queries\SortedQuery;

class SortableTraitTest extends ElasticSearcherTestCase
{
	public function testSettingSortingFields()
	{
		$this->hasSort(
            [
                'name',
                ['age' => ['order' => 'asc', 'mode' => 'avg']],
                ['date' => 'asc'],
            ],
			[
				'sort_fields' => [
					'name',
					'age' => ['order' => 'asc', 'mode' => 'avg'],
					'date' => 'asc',
				]
			]
		);
	}

	public function testSortingAFieldWithoutPredefinedFields()
	{
		$this->hasSort(
            [
                ['date' => 'desc'],
            ],
			null,
			['sort' => 'date', 'sort_direction' => 'desc']
		);
	}

	public function testSortingAFieldFromPredefinedFields()
	{
		$this->hasSort(
            [
                ['date' => 'desc'],
                ['name' => 'asc'],
                ['age' => ['order' => 'asc', 'mode' => 'avg']],
            ],
			[
				'sort_fields' => [
					'name' => 'asc',
					'age' => ['order' => 'asc', 'mode' => 'avg'],
					'date' => 'asc',
				]
			],
			['sort' => 'date', 'sort_direction' => 'desc']
		);
	}

	public function testSortingAnUndefinedFieldWithPredefinedFields()
	{
		$this->hasSort(
            [
                ['email' => 'asc'],
                ['name' => 'asc'],
                ['age' => ['order' => 'asc', 'mode' => 'avg']],
                ['date' => 'asc'],
            ],
			[
				'sort_fields' => [
					'name' => 'asc',
					'age' => ['order' => 'asc', 'mode' => 'avg'],
					'date' => 'asc',
				]
			],
			['sort' => 'email', 'sort_direction' => 'asc']
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
            [
                ['date' => 'desc'],
                'name',
                ['age' => ['order' => 'asc', 'mode' => 'avg']],
            ],
			$fields,
			['sort' => 'date', 'sort_direction' => null]
		);
		$this->hasSort(
            [
                ['age' => ['order' => 'asc', 'mode' => 'avg']],
                'name',
                ['date' => 'desc'],
            ],
			$fields,
			['sort' => 'age', 'sort_direction' => null]
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
                'name',
                ['age' => ['order' => 'asc', 'mode' => 'avg']],
                ['date' => 'desc'],
            ],
			[
				'sort_fields' => [
					'name',
					'age' => ['order' => 'asc', 'mode' => 'avg'],
					'date' => 'desc',
				]
			],
			['sort' => 'name']
		);
		$this->hasSort(
            [
                ['name' => 'desc'],
                ['age' => ['order' => 'asc', 'mode' => 'avg']],
                ['date' => 'desc'],
            ],
			$fields,
			['sort' => 'name', 'sort_direction' => 'desc']
		);
	}

	private function hasSort(array $expectedSort, ?array $predefinedFields = null, ?array $sortBy = null)
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
