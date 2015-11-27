<?php

namespace ElasticSearcher\Dummy\Queries;

use ElasticSearcher\Abstracts\AbstractQuery;
use ElasticSearcher\Dummy\Fragments\Filters\IDFilter;

class MovieWithIDXQuery extends AbstractQuery
{
	public function setup()
	{
		$this->searchIn('movies', 'movies');

		$this->set('query.filtered.filter', [new IDFilter($this->getData('id'))]);
	}
}
