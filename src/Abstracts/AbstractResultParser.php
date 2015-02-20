<?php

namespace ElasticSearcher\Abstracts;

use Illuminate\Support\Arr;

/**
 * Base class for result parsing.
 */
abstract class AbstractResultParser
{
	/**
	 * @var array
	 */
	private $rawResults = array();

	/**
	 * @param array $rawResults
	 */
	public function setRawResults(array $rawResults)
	{
		$this->rawResults = $rawResults;
	}

	/**
	 * @return array
	 */
	public function getRawResults()
	{
		return $this->rawResults;
	}

	/**
	 * Get a chunk of data from the raw results, using the dot notation.
	 * Example: $this->get('hits.max_score');
	 *
	 * @param string     $key
	 * @param null|mixed $default
	 *
	 * @return mixed
	 */
	public function get($key, $default = null)
	{
		return Arr::get($this->rawResults, $key, $default);
	}

	/**
	 * Total hits for this query.
	 *
	 * @return int
	 */
	public function getTotal()
	{
		return $this->get('hits.total');
	}

	/**
	 * Time it took to executed the query.
	 *
	 * @return int
	 */
	public function getTook()
	{
		return $this->get('took');
	}

	/**
	 * @return array
	 */
	public function getHits()
	{
		return $this->get('hits.hits');
	}

	/**
	 * Parse the raw results and convert to usable results.
	 * This could for example fetch models in an ORM, based on the hits.
	 */
	abstract function getResults();
}
