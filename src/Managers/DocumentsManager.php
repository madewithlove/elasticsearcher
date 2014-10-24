<?php

namespace ElasticSearcher\Managers;

use ElasticSearcher\Abstracts\ManagerAbstract;

/**
 * Manager for everything document related. Holds basic CRUD operations on documents.
 */
class DocumentsManager extends ManagerAbstract
{
	/**
	 * Create a document.
	 *
	 * @return array
	 *
	 * @param string $indexName
	 * @param string $type
	 * @param array  $data
	 */
	public function index($indexName, $type, array $data)
	{
		$index = $this->elasticSearcher->indicesManager()->getRegistered($indexName);

		$params = [
			'index' => $index->getName(),
			'type'  => $type,
			'body'  => $data
		];

		// If an ID exists in the data set, use it, otherwise let elasticsearch generate one.
		if (array_key_exists('id', $data)) {
			$params['id'] = $data['id'];
		}

		return $this->elasticSearcher->getClient()->index($params);
	}

	/**
	 * Index a set of documents.
	 *
	 * @param string $indexName
	 * @param string $type
	 * @param array  $data
	 */
	public function bulkIndex($indexName, $type, array $data)
	{
		foreach ($data as $item) {
			$this->index($indexName, $type, $item);
		}
	}

	/**
	 * @return array
	 *
	 * @param string $indexName
	 * @param string $type
	 * @param string $id
	 */
	public function delete($indexName, $type, $id)
	{
		$index = $this->elasticSearcher->indicesManager()->getRegistered($indexName);

		$params = [
			'index' => $index->getName(),
			'type'  => $type,
			'id'    => $id
		];

		return $this->elasticSearcher->getClient()->delete($params);
	}

	/**
	 * Partial updating of an existing document.
	 *
	 * @return array
	 *
	 * @param string $indexName
	 * @param string $type
	 * @param string $id
	 * @param array  $data
	 */
	public function update($indexName, $type, $id, array $data)
	{
		$index = $this->elasticSearcher->indicesManager()->getRegistered($indexName);

		$params = [
			'index' => $index->getName(),
			'type'  => $type,
			'id'    => $id,
			'body'  => ['doc' => $data]
		];

		return $this->elasticSearcher->getClient()->update($params);
	}

	/**
	 * @return bool
	 *
	 * @param string $indexName
	 * @param string $type
	 * @param string $id
	 */
	public function exists($indexName, $type, $id)
	{
		$index = $this->elasticSearcher->indicesManager()->getRegistered($indexName);

		$params = [
			'index' => $index->getName(),
			'type'  => $type,
			'id'    => $id,
		];

		return $this->elasticSearcher->getClient()->exists($params);
	}

	/**
	 * @return array
	 *
	 * @param string $indexName
	 * @param string $type
	 * @param string $id
	 */
	public function get($indexName, $type, $id)
	{
		$index = $this->elasticSearcher->indicesManager()->getRegistered($indexName);

		$params = [
			'index' => $index->getName(),
			'type'  => $type,
			'id'    => $id,
		];

		return $this->elasticSearcher->getClient()->get($params);
	}
}
