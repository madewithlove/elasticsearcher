<?php

namespace ElasticSearcher\Abstracts;

/**
 * Base class for fragments that can be used in the body of requests to Elasticsearch.
 */
abstract class AbstractFragment
{
	/**
	 * Body of the fragment to be executed. Should be the array as if you would pass it
	 * directly to the ElasticSearcher SDK.
	 *
	 * @var array
	 */
	protected $body;

	/**
	 * Should this fragment be merged with its parent, or simply be replaced.
	 * Useful for fragments on the root level of the query, for example adding sorting or pagination.
	 * Also useful for fragments that are part of bigger fragment, for example analyzers.
	 *
	 * @var bool
	 */
	public $mergeWithParent = false;

	/**
	 * @param array $body
	 */
	public function setBody(array $body)
	{
		$this->body = $body;
	}

	/**
	 * @return array
	 */
	public function getBody()
	{
		return $this->body;
	}
}
