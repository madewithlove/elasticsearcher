<?php

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
					'first_name' => ['type' => 'string'],
					'last_name' => ['type' => 'string']
				]
			],
			'producers' => [
				'properties' => [
					'id' => ['type' => 'integer'],
					'name' => ['type' => 'string']
				]
			]
		]);
	}
}
