<?php

namespace ElasticSearcher\Dummy\Queries;

use ElasticSearcher\Abstracts\AbstractQuery;
use ElasticSearcher\ElasticSearcher;
use ElasticSearcher\Fragments\Traits\PaginatedTrait;

class PaginatedMoviesFrom2014Query extends AbstractQuery
{
	use PaginatedTrait;

	private $pageNumber;

	public function __construct(\ElasticSearcher\ElasticSearcher $searcher, $page = 3)
  {
    parent::__construct($searcher);
    $this->pageNumber = $page;
  }

  public function setup()
	{
		$this->paginate($this->pageNumber, 10);
	}
}
