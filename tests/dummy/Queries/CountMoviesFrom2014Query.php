<?php

use ElasticSearcher\Abstracts\AbstractQuery;
use ElasticSearcher\Fragments\Filters\TermFilter;

class CountMoviesFrom2014Query extends AbstractQuery
{
	public function setup()
	{
		$this->searchIn('movies', 'movies');

		$this->set('query.bool.filter', [new TermFilter('year', 2014)]);

		$this->setSearchType('count');
	}
}
