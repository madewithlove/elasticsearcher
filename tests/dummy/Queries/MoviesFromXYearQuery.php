<?php

use ElasticSearcher\Abstracts\QueryAbstract;
use ElasticSearcher\Filters\TermFilter;

class MoviesFromXYearQuery extends QueryAbstract
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
		$year = $this->getData('year');
		if ($year) {
			return new TermFilter('year', $year);
		}
	}
}
