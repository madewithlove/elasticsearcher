<?php

namespace ElasticSearcher\Dummy\Indexes;

use ElasticSearcher\Abstracts\AbstractIndex;

class MoviesIndex extends AbstractIndex
{
	public function getName()
	{
		return 'movies';
	}

	public function setup()
	{
		$this->setTypes([
			'movies' => [
				'properties' => [
					'id' => ['type' => 'integer'],
					'name' => ['type' => 'string'],
					'year' => ['type' => 'integer'],
				]
			]
		]);
	}
}
