<?php

namespace ElasticSearcher\Abstracts;

use ElasticSearcher\Traits\BodyTrait;

/**
 * Base class for fragments that can be used in the body of requests to Elasticsearch.
 */
abstract class AbstractFragment
{
	use BodyTrait;

	/**
	 * Should this fragment be merged with its parent, or simply be replaced.
	 * Useful for fragments on the root level of the query, for example adding sorting or pagination.
	 * Also useful for fragments that are part of bigger fragment, for example analyzers.
	 *
	 * @var bool
	 */
	public $mergeWithParent = false;
}
