<?php

use ElasticSearcher\Abstracts\AbstractQuery;
use ElasticSearcher\Fragments\Traits\SortableTrait;

class SortedQuery extends AbstractQuery
{
	use SortableTrait;

	public function setup()
	{
		if ($fields = $this->getData('sort_fields')) {
			$this->sort($fields);
		}

		if ($fieldName = $this->getData('sort')) {
			$this->sortBy($this->getData('sort'), $this->getData('sort_direction'));
		}
	}
}
