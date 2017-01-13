<?php

namespace ElasticSearcher\Dummy\Indexes;

use ElasticSearcher\Abstracts\AbstractIndex;

class AuthorsIndex extends AbstractIndex
{
	public function getName()
	{
		return 'authors';
	}

	public function setup()
	{
		$this->setTypes([
			'directors' => [
				'properties' => [
					'id' => ['type' => 'integer'],
					'first_name' => ['type' => 'text'],
					'last_name' => ['type' => 'text']
				]
			],
			'producers' => [
				'properties' => [
					'id' => ['type' => 'integer'],
					'name' => ['type' => 'text']
				]
			]
		]);
	}
}
