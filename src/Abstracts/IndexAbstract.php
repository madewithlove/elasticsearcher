<?php

namespace ElasticSearcher\Abstracts;

/**
 * Base class for indexes.
 */
abstract class IndexAbstract
{
	/**
	 * @return string
	 */
	abstract public function getName();

	/**
	 * @return array
	 */
	abstract public function getMappings();

	/**
	 * @return array
	 */
	public function getBody()
	{
		return [
			'mappings' => $this->getMappings()
		];
	}

	/**
	 * @param string $type
	 *
	 * @return mixed
	 */
	public function getMapping($type)
	{
		$mappings = $this->getMappings();

		return $mappings[$type];
	}
}
