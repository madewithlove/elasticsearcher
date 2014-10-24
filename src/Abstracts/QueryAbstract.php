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
	 * Data that can be used when building a query.
	 *
	 * @var array
	 */
	protected $data = array();

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
	 * Add data that can be accessed during query building.
	 *
	 * @param array $data
	 */
	public function addData(array $data)
	{
		$this->data = array_merge($this->data, $data);
	}

	/**
	 * @return array
	 *
	 * @param null|string
	 */
	public function getData($key = null)
	{
		if ($key !== null) {
			return array_key_exists($key, $this->data) ? $this->data[$key] : null;
		}

		return $this->data;
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
		$query['body']  = $this->parseAbstracts($this->body);

		return $query;
	}

	/**
	 * Traverses the body and checks if there are any abstracts (filters, queries) to be replaced with their
	 * body.
	 *
	 * @param array $body
	 *
	 * @return array
	 */
	protected function parseAbstracts(array $body)
	{
		// Replace all abstracts with their body.
		array_walk_recursive($body, function (&$item, $key) {
			if ($item instanceof FilterAbstract) {
				$item = $item->getBody();
			}
		});

		return $body;
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
