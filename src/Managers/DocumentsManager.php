<?php

namespace ElasticSearcher\Managers;

use ElasticSearcher\ElasticSearcher;

/**
 * Manager for everything document related. Holds basic CRUD operations on documents.
 */
class DocumentsManager
{
	/**
	 * @var ElasticSearcher
	 */
	private $elasticSearcher;

	/**
	 * @param ElasticSearcher $elasticSearcher
	 */
	public function __construct(ElasticSearcher $elasticSearcher)
	{
		$this->elasticSearcher = $elasticSearcher;
	}

	/**
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
}
