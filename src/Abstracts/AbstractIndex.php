<?php

namespace ElasticSearcher\Abstracts;

use ElasticSearcher\Parsers\FragmentParser;

/**
 * Base class for indexes.
 */
abstract class AbstractIndex
{
	/**
	 * @var FragmentParser
	 */
	protected $fragmentParser;

	/**
	 * @return string
	 */
	abstract public function getName();

	/**
	 * @return array
	 */
	abstract public function getTypes();

	/**
	 */
	public function __construct()
	{
		$this->fragmentParser = new FragmentParser();
	}

	/**
	 * @return array
	 */
	public function getBody()
	{
		$body = [
			'settings' => $this->getSettings(),
			'mappings' => $this->getTypes(),
		];

		// Replace fragments with their raw body.
		$body = $this->fragmentParser->parse($body);

		return $body;
	}

	/**
	 * @return array
	 */
	public function getSettings()
	{
		return null;
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
