<?php

namespace ElasticSearcher\ValueObjects;

class Arr
{
	/**
	 * @var array
	 */
	private $array;

	/**
	 * @param array $array
	 */
	public function __construct(array $array = [])
	{
		$this->array = $array;
	}

	/**
	 * @param string $name
	 * @param null|mixed $default
	 *
	 * @return mixed
	 */
	public function get($name, $default = null)
	{
		$keys = explode('.', $name);
		$array = $this->array;

		foreach ($keys as $key) {
			if (isset($array[$key])) {
				$array = $array[$key];
			} else {
				return $default;
			}
		}

		return $array;
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function set($name, $value)
	{
		$keys = explode('.', $name);
		$array = &$this->array;

		foreach ($keys as $key) {
			if (count($keys) == 1) {
				$array[$key] = $value;
			} else {
				if (!array_key_exists($key, $array)) {
					$array[$key] = [];
				}

				array_shift($keys);
				$array = &$array[$key];
			}
		}
	}

	/**
	 * @return array
	 */
	public function all()
	{
		return $this->array;
	}
}
