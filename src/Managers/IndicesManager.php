<?php

namespace ElasticSearcher\Managers;

use ElasticSearcher\Abstracts\AbstractIndex;
use ElasticSearcher\Abstracts\AbstractManager;
use Exception;

/**
 * Manager for everything index related. Holds a container for
 * used indexes. Also holds basic CRUD operations on those indexes.
 */
class IndicesManager extends AbstractManager
{
	/**
	 * @var array
	 */
	private $indices = array();

	// ----------------------------------------
	// Registered indices.
	// ----------------------------------------
	/**
	 * @return AbstractIndex
	 *
	 * @param AbstractIndex $index
	 */
	public function register(AbstractIndex $index)
	{
		$this->indices[$index->getName()] = $index;

		return $index;
	}

	/**
	 * @param array $indices
	 */
	public function registerIndices(array $indices)
	{
		foreach ($indices as $index) {
			$this->register($index);
		}
	}

	/**
	 * @return AbstractIndex
	 *
	 * @param string $indexName
	 */
	public function unregister($indexName)
	{
		if ($this->isRegistered($indexName)) {
			unset($this->indices[$indexName]);
		}
	}

	/**
	 * @return bool
	 *
	 * @param string $indexName
	 */
	public function isRegistered($indexName)
	{
		return array_key_exists($indexName, $this->indices);
	}

	/**
	 * @return array
	 */
	public function registeredIndices()
	{
		return $this->indices;
	}

	/**
	 * Get a registered index. When not found it will throw an exception.
	 * If you do not want the exception being thrown, use getRegistered first.
	 *
	 * @return AbstractIndex
	 * @throws Exception
	 *
	 * @param string $indexName
	 */
	public function getRegistered($indexName)
	{
		if (!$this->isRegistered($indexName)) {
			throw new Exception('Index ['.$indexName.'] could not be found in the register of the indices manager.');
		}

		return $this->indices[$indexName];
	}

	// ----------------------------------------
	// Actions to the ElasticSearch server.
	// ----------------------------------------
	/**
	 * @return mixed
	 */
	public function indices()
	{
		return $this->elasticSearcher->getClient()->indices()->getMapping();
	}

	/**
	 * @return array
	 *
	 * @param string $indexName
	 */
	public function get($indexName)
	{
		$index = $this->getRegistered($indexName);

		$params = [
			'index' => $index->getInternalName()
		];

		return $this->elasticSearcher->getClient()->indices()->getMapping($params);
	}

	/**
	 * @param string $indexName
	 */
	public function create($indexName)
	{
		$index = $this->getRegistered($indexName);

		$params = [
			'index' => $index->getInternalName(),
			'body'  => $index->getBody()
		];

		$this->elasticSearcher->getClient()->indices()->create($params);
	}

	/**
	 * Update the index. This should be used when wanting to reflect changes
	 * in the Index object with the elasticsearch server.
	 *
	 * @param string $indexName
	 */
	public function update($indexName)
	{
		$index = $this->getRegistered($indexName);

		$this->elasticSearcher->getClient()->indices()->putMapping([
			'index' => $index->getInternalName(),
			'body'  => $index->getMappings(),
		]);
	}

	/**
	 * @param string $indexName
	 */
	public function delete($indexName)
	{
		$index = $this->getRegistered($indexName);

		$params = [
			'index' => $index->getInternalName()
		];

		$this->elasticSearcher->getClient()->indices()->delete($params);
	}

	/**
	 * @return bool
	 *
	 * @param string $indexName
	 */
	public function exists($indexName)
	{
		$index = $this->getRegistered($indexName);

		$params = [
			'index' => $index->getInternalName()
		];

		return $this->elasticSearcher->getClient()->indices()->exists($params);
	}
}
