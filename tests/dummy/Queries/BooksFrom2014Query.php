<?php

namespace ElasticSearcher\Dummy\Queries;

use ElasticSearcher\Abstracts\AbstractQuery;
use ElasticSearcher\Fragments\Queries\TermQuery;

class BooksFrom2014Query extends AbstractQuery
{
	public function setup()
	{
		$this->searchIn('books');

		$this->set('query.bool.filter', [new TermQuery('year', 2014)]);
	}
}
