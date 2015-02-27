<?php

use ElasticSearcher\Abstracts\AbstractQuery;
use ElasticSearcher\Fragments\Filters\TermFilter;

class MoviesFrom2014Query extends AbstractQuery
{
	public function setup()
	{
		$this->searchIn('movies', 'movies');

		$body = array(
			'query' => array(
				'filtered' => array(
					'filter' => array(
						new TermFilter('year', 2014)
					)
				)
			)
		);

		$this->setBody($body);
	}
}
