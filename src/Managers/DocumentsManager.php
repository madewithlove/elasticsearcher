<?php

namespace ElasticSearcher\Managers;

use ElasticSearcher\Abstracts\AbstractManager;
use ElasticSearcher\Abstracts\AbstractQuery;

/**
 * Manager for everything document related. Holds basic CRUD operations on documents.
 */
class DocumentsManager extends AbstractManager
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
			'index' => $index->getInternalName(),
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
		$params = ['body' => []];

		foreach ($data as $item) {
			$header = [
				'_index' => $indexName,
				'_type' => $type,
			];

			if (array_key_exists('id', $item)) {
				$header['_id'] = $item['id'];
			}

			// The bulk operation expects two JSON objects for each item
			// the first one should describe the operation, index, type
			// and ID. The later one is the document body.
			$params['body'][] = ['index' => $header];
			$params['body'][] = $item;
		}

		$this->elasticSearcher->getClient()->bulk($params);
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
			'index' => $index->getInternalName(),
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
			'index' => $index->getInternalName(),
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
			'index' => $index->getInternalName(),
			'type'  => $type,
			'id'    => $id,
		];

		return $this->elasticSearcher->getClient()->exists($params);
	}

	/**
	 * Update a document. Create it if it doesn't exist.
	 *
	 * @return array
	 *
	 * @param string $indexName
	 * @param string $type
	 * @param string $id
	 * @param array $data
	 */
	public function updateOrIndex($indexName, $type, $id, array $data)
	{
		if ($this->exists($indexName, $type, $id)) {
			return $this->update($indexName, $type, $id, $data);
		} else {
			return $this->index($indexName, $type, $data);
		}
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
			'index' => $index->getInternalName(),
			'type'  => $type,
			'id'    => $id,
		];

		return $this->elasticSearcher->getClient()->get($params);
	}

	/**
	 * @param AbstractQuery $query
	 *
	 * @return array
	 */
	public function deleteByQuery(AbstractQuery $query)
	{
		return $this->elasticSearcher->getClient()->deleteByQuery($query->getRawQuery());
	}
}
