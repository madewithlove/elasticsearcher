<?php

use ElasticSearcher\Abstracts\AbstractQuery;
use ElasticSearcher\Fragments\Filters\TermFilter;

class MoviesFromXYearQuery extends AbstractQuery
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
