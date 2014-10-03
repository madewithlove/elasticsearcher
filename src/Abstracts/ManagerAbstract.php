<?php

namespace ElasticSearcher\Abstracts;

use ElasticSearcher\ElasticSearcher;

/**
 * Base class for managers.
 */
abstract class ManagerAbstract
{
	/**
	 * @var ElasticSearcher
	 */
	protected $elasticSearcher;

	/**
	 * @param ElasticSearcher $elasticSearcher
	 */
	public function __construct(ElasticSearcher $elasticSearcher)
	{
		$this->elasticSearcher = $elasticSearcher;
	}
}
