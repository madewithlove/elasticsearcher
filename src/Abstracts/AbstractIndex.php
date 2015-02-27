<?php

namespace ElasticSearcher\Abstracts;

/**
 * Base class for indexes.
 */
abstract class AbstractIndex
{
	/**
	 * @return string
	 */
	abstract public function getName();

	/**
	 * @return array
	 */
	abstract public function getTypes();

	/**
	 * @return array
	 */
	public function getBody()
	{
		return [
			'mappings' => $this->getTypes()
		];
	}

	/**
	 * @param string $type
	 *
	 * @return mixed
	 */
	public function getType($type)
	{
		$types = $this->getTypes();

		return $types[$type];
	}
}
