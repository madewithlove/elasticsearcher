<?php

namespace ElasticSearcher\Dummy\Queries;

use ElasticSearcher\Abstracts\AbstractQuery;
use ElasticSearcher\Fragments\Filters\TermFilter;

class MoviesFrom2014Query extends AbstractQuery
{
	public function setup()
	{
		$this->searchIn('movies', 'movies');

		$this->set('query.filtered.filter', [new TermFilter('year', 2014)]);
	}
}
