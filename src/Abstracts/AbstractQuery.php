<?php

namespace ElasticSearcher\Abstracts;

use ElasticSearcher\ElasticSearcher;
use ElasticSearcher\Parsers\ArrayResultParser;
use ElasticSearcher\Parsers\FragmentParser;

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
	protected $indices = [];

	/**
	 * Types on which the query should be executed.
	 *
	 * @var array
	 */
	protected $types = [];

	/**
	 * Body of the query to execute.
	 *
	 * @var array
	 */
	protected $body = [];

	/**
	 * Data that can be used when building a query.
	 *
	 * @var array
	 */
	protected $data = [];

	/**
	 * @var AbstractResultParser
	 */
	protected $resultParser;

	/**
	 * @var FragmentParser
	 */
	protected $fragmentParser;

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
		$this->fragmentParser = new FragmentParser();
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
	 * @return mixed
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
	 * Define on which indices and types the query should be run.
	 *
	 * @param string|array $index
	 * @param null|string|array $type
	 */
	public function searchIn($index, $type = null)
	{
		// Reset the current state, in case the same instance is re-used.
		$this->indices = [];
		$this->types = [];

		$this->searchInIndices((array) $index);

		if ($type !== null) {
			$this->searchInTypes((array) $type);
		}
	}

	/**
	 * @param array $indices
	 */
	protected function searchInIndices(array $indices)
	{
		foreach ($indices as $index) {
			$index = $this->searcher->indicesManager()->getRegistered($index);

			$this->indices[] = $index->getName();
		}

		// Remove doubles.
		$this->indices = array_unique($this->indices);
	}

	/**
	 * @param array $types
	 */
	protected function searchInTypes(array $types)
	{
		foreach ($types as $type) {
			$this->types[] = $type;
		}

		// Remove doubles.
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
		$this->setup();

		$query = array();

		// Index always needs to be provided. _all means a cross index search.
		$query['index'] = empty($this->indices) ? '_all' : implode(',', array_values($this->indices));

		// Type is not required, will search in the entire index.
		if (!empty($this->types)) {
			$query['type'] = implode(',', array_values($this->types));
		}

		// Replace Fragments with their raw body.
		$query['body'] = $this->fragmentParser->parse($this->body);

		return $query;
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
		$query = $this->buildQuery();

		// Execute the query.
		$rawResults = $this->searcher->getClient()->search($query);

		// Pass response to the class that will do something with it.
		$resultParser = $this->getResultParser();
		$resultParser->setRawResults($rawResults);

		return $resultParser;
	}
}
