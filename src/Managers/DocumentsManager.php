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
	 * @param string $reference
	 * @param string $type
	 * @param array  $data
	 */
	public function index($reference, $type, array $data)
	{
		if ($this->elasticSearcher->indicesManager()->isRegistered($reference)) {
			$index = $this->elasticSearcher->indicesManager()->registeredIndices()[$reference];

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
	}

	/**
	 * @return array
	 *
	 * @param string $reference
	 * @param string $type
	 * @param string $id
	 */
	public function delete($reference, $type, $id)
	{
		if ($this->elasticSearcher->indicesManager()->isRegistered($reference)) {
			$index = $this->elasticSearcher->indicesManager()->registeredIndices()[$reference];

			$params = [
				'index' => $index->getName(),
				'type'  => $type,
				'id'    => $id
			];

			return $this->elasticSearcher->getClient()->delete($params);
		}
	}

	/**
	 * Partial updating of an existing document.
	 *
	 * @return array
	 *
	 * @param string $reference
	 * @param string $type
	 * @param string $id
	 * @param array  $data
	 */
	public function update($reference, $type, $id, array $data)
	{
		if ($this->elasticSearcher->indicesManager()->isRegistered($reference)) {
			$index = $this->elasticSearcher->indicesManager()->registeredIndices()[$reference];

			$params = [
				'index' => $index->getName(),
				'type'  => $type,
				'id'    => $id,
				'body'  => ['doc' => $data]
			];

			return $this->elasticSearcher->getClient()->update($params);
		}
	}
}
