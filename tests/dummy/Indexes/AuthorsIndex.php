<?php

use ElasticSearcher\Abstracts\IndexAbstract;

class AuthorsIndex extends IndexAbstract
{
	public function getName()
	{
		return 'authors';
	}

	public function getTypes()
	{
		return array(
			'directors' => array(
				'properties' => array(
					'id'         => array(
						'type' => 'integer'
					),
					'first_name' => array(
						'type' => 'string'
					),
					'last_name'  => array(
						'type' => 'string'
					)
				)
			),
			'producers'  => array(
				'properties' => array(
					'id'   => array(
						'type' => 'integer'
					),
					'name' => array(
						'type' => 'string'
					)
				)
			)
		);
	}
}
