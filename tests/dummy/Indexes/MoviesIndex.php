<?php

use ElasticSearcher\Abstracts\IndexAbstract;

class MoviesIndex extends IndexAbstract
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
