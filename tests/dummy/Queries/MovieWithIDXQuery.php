<?php

use ElasticSearcher\Abstracts\AbstractQuery;

class MovieWithIDXQuery extends AbstractQuery
{
	public function setup()
	{
		$this->searchIn('movies', 'movies');

		$this->set('query.bool.filter', [new IDFilter($this->getData('id'))]);
	}
}
