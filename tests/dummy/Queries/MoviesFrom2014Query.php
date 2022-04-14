<?php

namespace ElasticSearcher\Dummy\Queries;

use ElasticSearcher\Abstracts\AbstractQuery;
use ElasticSearcher\Fragments\Queries\TermQuery;

class MoviesFrom2014Query extends AbstractQuery
{
	public function setup()
	{
		$this->searchIn('movies');

		$this->set('query.bool.filter', [new TermQuery('year', 2014)]);
	}
}
