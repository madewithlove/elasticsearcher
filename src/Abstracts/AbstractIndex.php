<?php

namespace ElasticSearcher\Abstracts;

use ElasticSearcher\Parsers\FragmentParser;
use ElasticSearcher\Traits\BodyTrait;

/**
 * Base class for indexes.
 */
abstract class AbstractIndex
{
	use BodyTrait;

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
	abstract public function setup();

	/**
	 */
	public function __construct()
	{
		$this->fragmentParser = new FragmentParser();

		$this->setup();
	}

	/**
	 * @return array
	 */
	public function getBody()
	{
		// Replace fragments with their raw body.
		return $this->fragmentParser->parse($this->body);
	}

	/**
	 * @param array $types
	 *
	 * @return array
	 */
	public function setTypes(array $types)
	{
		return $this->set('mappings', $types);
	}

	/**
	 * @return array
	 */
	public function getTypes()
	{
		return $this->get('mappings');
	}

	/**
	 * @param array $settings
	 *
	 * @return array
	 */
	public function setSettings(array $settings)
	{
		return $this->set('settings', $settings);
	}

	/**
	 * @return array
	 */
	public function getSettings()
	{
		return $this->get('settings');
	}

	/**
	 * @param string $type
	 *
	 * @return array
	 */
	public function getType($type)
	{
		return $this->get('mappings.'.$type);
	}
}
