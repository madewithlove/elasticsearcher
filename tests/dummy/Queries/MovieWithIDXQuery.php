<?php

use ElasticSearcher\Abstracts\AbstractQuery;

class MovieWithIDXQuery extends AbstractQuery
{
	public function setup()
	{
		$this->searchIn('movies', 'movies');

		$this->set('query.filtered.filter', [new IDFilter($this->getData('id'))]);
	}
}
