<?php

namespace ElasticSearcher\Abstracts;

/**
 * Base class for filters.
 */
abstract class FilterAbstract
{
	/**
	 * Body of the filter to be executed. Should be the array as if you would pass it
	 * directly to the ElasticSearcher SDK.
	 *
	 * @var array
	 */
	protected $body;

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
