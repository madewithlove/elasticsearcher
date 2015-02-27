<?php

use ElasticSearcher\Abstracts\AbstractIndex;

class MoviesIndex extends AbstractIndex
{
	public function getName()
	{
		return 'movies';
	}

	public function getTypes()
	{
		return array(
			'movies' => array(
				'properties' => array(
					'id'   => array(
						'type' => 'integer'
					),
					'name' => array(
						'type' => 'string'
					),
					'year' => array(
						'type' => 'integer',
					)
				)
			)
		);
	}
}
