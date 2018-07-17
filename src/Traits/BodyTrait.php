<?php

namespace ElasticSearcher\Traits;

use ArrayHelpers\Arr;

/**
 * A body can be an entire query or just a chunk of query (fragment).
 * Any entity (query, fragment, index, ...) that builds request body's should
 * use this trait.
 *
 * @package ElasticSearcher\Traits
 */
trait BodyTrait
{
	/**
	 * Body of the query to execute.
	 *
	 * @var array
	 */
	public $body = [];

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

	/**
	 * Set a value in the body using the dotted notation.
	 *
	 * @param string $key
	 * @param mixed $value
	 *
	 * @return $this
	 */
	public function set($key, $value)
	{
		Arr::set($this->body, $key, $value);

		return $this;
	}

	/**
	 * Get a value in the body using the dotted notation.
	 *
	 * @param string $key
	 * @param mixed $default
	 *
	 * @return $this
	 */
	public function get($key, $default = null)
	{
		return Arr::get($this->body, $key, $default);
	}
}
