<?php

namespace ElasticSearcher\Abstracts;

use ElasticSearcher\ElasticSearcher;

/**
 * Base class for queries.
 */
abstract class QueryAbstract
{
	/**
	 * @var ElasticSearcher
	 */
	protected $searcher;

	/**
	 * Indices on which the query should be executed.
	 *
	 * @var array
	 */
	protected $indices;

	/**
	 * Types on which the query should be executed.
	 *
	 * @var array
	 */
	protected $types;

	/**
	 * Body of the query to execute.
	 *
	 * @var array
	 */
	protected $body;

	/**
	 * Prepare the query. Add filters, sorting, ....
	 */
	abstract protected function setup();

	/**
	 * @param ElasticSearcher $searcher
	 */
	public function __construct(ElasticSearcher $searcher)
	{
		$this->searcher = $searcher;
	}

	/**
	 * Search in an index and/or type.
	 *
	 * @param string      $index
	 * @param null|string $type
	 */
	protected function searchIn($index, $type = null)
	{
		$this->searchInIndex($index);

		if ($type !== null) {
			$this->searchInType($type);
		}
	}

	/**
	 * @param string $index
	 */
	protected function searchInIndex($index)
	{
		$index = $this->searcher->indicesManager()->getRegistered($index);

		$this->indices[] = $index->getName();

		// Make sure we have no doubles.
		$this->indices = array_unique($this->indices);
	}

	/**
	 * @param string $type
	 */
	protected function searchInType($type)
	{
		$this->types[] = $type;

		// Make sure we have no doubles.
		$this->types = array_unique($this->types);
	}

	/**
	 * @param array $body
	 */
	protected function setBody(array $body)
	{
		$this->body = $body;
	}

	/**
	 * Build the query by adding all chunks together.
	 *
	 * @return array
	 */
	protected function buildQuery()
	{
		$query = array();

		$query['index'] = empty($this->indices) ? '_all' : implode(',', array_values($this->indices));
		$query['type']  = empty($this->types) ? '_all' : implode(',', array_values($this->types));
		$query['body']  = $this->body;

		return $query;
	}

	/**
	 * Build and execute the query.
	 *
	 * @return array
	 */
	public function getResults()
	{
		$this->setup();

		$query = $this->buildQuery();

		return $this->searcher->getClient()->search($query);
	}
}
