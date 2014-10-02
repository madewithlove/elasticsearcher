<?php

namespace ElasticSearcher;

/**
 * Container for environment variables.
 */
class Environment
{
	/**
	 * @var array
	 */
	protected $variables;

	/**
	 * @param array $variables
	 */
	public function __construct(array $variables)
	{
		$this->variables = $variables;
	}

	/**
	 * @param string $name
	 *
	 * @return null|mixed
	 */
	public function __get($name)
	{
		return array_key_exists($name, $this->variables) ? $this->variables[$name] : null;
	}

	/**
	 * @param string $name
	 * @param mixed  $value
	 *
	 * @return mixed
	 */
	public function __set($name, $value)
	{
		return $this->variables[$name] = $value;
	}
}
