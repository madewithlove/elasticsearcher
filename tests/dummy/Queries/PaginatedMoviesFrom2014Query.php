<?php

namespace ElasticSearcher\Dummy\Queries;

use ElasticSearcher\Abstracts\AbstractQuery;
use ElasticSearcher\ElasticSearcher;
use ElasticSearcher\Fragments\Traits\PaginatedTrait;

class PaginatedMoviesFrom2014Query extends AbstractQuery
{
	use PaginatedTrait;

	public function setup()
	{
		$page = isset($this->data['page']) ? $this->data['page'] : 3;
		$this->paginate($page, 10);
	}
}
