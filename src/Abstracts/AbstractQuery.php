<?php

namespace ElasticSearcher\Abstracts;

use ElasticSearcher\ElasticSearcher;
use ElasticSearcher\ResultParsers\ArrayResultParser;

/**
 * Base class for queries.
 */
abstract class AbstractQuery
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
	protected $body = array();

	/**
	 * Data that can be used when building a query.
	 *
	 * @var array
	 */
	protected $data = array();

	/**
	 * @var AbstractResultParser
	 */
	protected $resultParser;

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

		// Default result parser.
		$this->parseResultsWith(new ArrayResultParser());
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

		// Index always needs to be provided. _all means a cross index search.
		$query['index'] = empty($this->indices) ? '_all' : implode(',', array_values($this->indices));

		// Type is not required, will search in the entire index.
		if (!empty($this->types)) {
			$query['type'] = implode(',', array_values($this->types));
		}

		$query['body'] = $this->parseAbstracts($this->body);

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
			if ($item instanceof AbstractFilter) {
				$item = $item->getBody();
			}
		});

		return $body;
	}

	/**
	 * @return mixed
	 */
	public function getResultParser()
	{
		return $this->resultParser;
	}

	/**
	 * Indices we are searching in.
	 *
	 * @return array
	 */
	public function getIndices()
	{
		return $this->indices;
	}

	/**
	 * Types we are searching in.
	 *
	 * @return array
	 */
	public function getTypes()
	{
		return $this->types;
	}

	/**
	 * Get the query after being build.
	 * This is what will be sent to the elasticsearch SDK.
	 *
	 * @return array
	 */
	public function getRawQuery()
	{
		return $this->buildQuery();
	}

	/**
	 * @param AbstractResultParser $resultParser
	 */
	public function parseResultsWith(AbstractResultParser $resultParser)
	{
		$this->resultParser = $resultParser;
	}

	/**
	 * Build and execute the query.
	 *
	 * @return AbstractResultParser
	 */
	public function run()
	{
		$this->setup();

		$query = $this->buildQuery();

		// Execute the query.
		$rawResults = $this->searcher->getClient()->search($query);

		// Pass response to the class that will do something with it.
		$resultParser = $this->getResultParser();
		$resultParser->setRawResults($rawResults);

		return $resultParser;
	}
}
