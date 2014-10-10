<?php

namespace ElasticSearcher\Managers;

use ElasticSearcher\Abstracts\IndexAbstract;
use ElasticSearcher\Abstracts\ManagerAbstract;

/**
 * Manager for everything index related. Holds a container for
 * used indexes. Also holds basic CRUD operations on those indexes.
 */
class IndicesManager extends ManagerAbstract
{
	/**
	 * @var array
	 */
	private $indices = array();

	/**
	 * @return IndexAbstract
	 *
	 * @param IndexAbstract $index
	 */
	public function register(IndexAbstract $index)
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
	 * @return IndexAbstract
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
	 * @return mixed
	 */
	public function indices()
	{
		return $this->elasticSearcher->getClient()->indices()->getMapping(['_all']);
	}

	/**
	 * @param string $indexName
	 */
	public function create($indexName)
	{
		if ($this->isRegistered($indexName)) {
			$index = $this->indices[$indexName];

			$params = [
				'index' => $index->getName(),
				'body'  => $index->getBody()
			];

			$this->elasticSearcher->getClient()->indices()->create($params);
		}
	}

	/**
	 * Update the index and all its types. This should be used when wanting to reflect changes
	 * in the Index object with the elasticsearch server.
	 *
	 * @param string $indexName
	 */
	public function update($indexName)
	{
		if ($this->isRegistered($indexName)) {
			$index = $this->indices[$indexName];

			foreach ($index->getTypes() as $type => $typeBody) {
				$params = [
					'index' => $index->getName(),
					'type'  => $type,
					'body'  => [$type => $typeBody]
				];

				$this->elasticSearcher->getClient()->indices()->putMapping($params);
			}
		}
	}

	/**
	 * @param string $indexName
	 */
	public function delete($indexName)
	{
		if ($this->isRegistered($indexName)) {
			$index = $this->indices[$indexName];

			$params = [
				'index' => $index->getName()
			];

			$this->elasticSearcher->getClient()->indices()->delete($params);
		}
	}

	/**
	 * @param string $indexName
	 * @param string $type
	 */
	public function deleteType($indexName, $type)
	{
		if ($this->isRegistered($indexName)) {
			$index = $this->indices[$indexName];

			$params = [
				'index' => $index->getName(),
				'type'  => $type
			];

			$this->elasticSearcher->getClient()->indices()->deleteMapping($params);
		}
	}

	/**
	 * @return bool
	 *
	 * @param string $indexName
	 */
	public function exists($indexName)
	{
		if ($this->isRegistered($indexName)) {
			$index = $this->indices[$indexName];

			$params = [
				'index' => $index->getName()
			];

			return $this->elasticSearcher->getClient()->indices()->exists($params);
		}

		return false;
	}

	/**
	 * @return bool
	 *
	 * @param string $indexName
	 * @param string $type
	 */
	public function existsType($indexName, $type)
	{
		if ($this->isRegistered($indexName)) {
			$index = $this->indices[$indexName];

			$params = [
				'index' => $index->getName(),
				'type'  => $type
			];

			return $this->elasticSearcher->getClient()->indices()->existsType($params);
		}

		return false;
	}
}
