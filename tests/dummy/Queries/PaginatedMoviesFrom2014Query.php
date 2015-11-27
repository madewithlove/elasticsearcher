<?php

namespace ElasticSearcher\Dummy\Queries;

use ElasticSearcher\Abstracts\AbstractQuery;
use ElasticSearcher\Fragments\Traits\PaginatedTrait;

class PaginatedMoviesFrom2014Query extends AbstractQuery
{
	use PaginatedTrait;

	public function setup()
	{
		$this->paginate(3, 10);
	}
}
