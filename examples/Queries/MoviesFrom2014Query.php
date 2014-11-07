<?php

use ElasticSearcher\Abstracts\QueryAbstract;
use ElasticSearcher\Filters\TermFilter;

class MoviesFrom2014Query extends QueryAbstract
{
	public function setup()
	{
		$this->searchIn('movies', 'movies');

		$body = array(
			'query' => array(
				'filtered' => array(
					'filter' => array(
						$this->parseYear()
					)
				)
			)
		);

		$this->setBody($body);
	}

	public function parseYear()
	{
		return new TermFilter('year', 2014);
	}
}
