<?php

namespace ElasticSearcher\Dummy\Queries;

use ElasticSearcher\Abstracts\AbstractQuery;
use ElasticSearcher\Fragments\Filters\TermFilter;

class BooksFrom2014Query extends AbstractQuery
{
	public function setup()
	{
		$this->searchIn('books', 'books');

		$this->set('query.filtered.filter', [new TermFilter('year', 2014)]);
	}
}
