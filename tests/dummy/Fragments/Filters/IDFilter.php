<?php

use ElasticSearcher\Abstracts\AbstractFragment;
use ElasticSearcher\Fragments\Queries\TermQuery;

class IDFilter extends AbstractFragment
{
	/**
	 * @var int
	 */
	private $id;

	/**
	 * @param int $id
	 */
	public function __construct($id)
	{
		$this->id = $id;
	}

	public function getBody()
	{
		return new TermQuery('id', $this->id);
	}
}
