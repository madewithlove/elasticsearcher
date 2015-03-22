<?php

use ElasticSearcher\Abstracts\AbstractQuery;

class MovieWithIDXQuery extends AbstractQuery
{
	public function setup()
	{
		$this->searchIn('movies', 'movies');

		$body = array(
			'query' => array(
				'filtered' => array(
					'filter' => array(
						new IDFilter($this->getData('id'))
					)
				)
			)
		);

		$this->setBody($body);
	}
}
