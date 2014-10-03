<?php

namespace ElasticSearcher;

/**
 * Manager for everything index related. Holds a container for
 * used indexes. Also holds basic CRUD operations on those indexes.
 */
class IndicesManager
{
	/**
	 * @var array
	 */
	private $indices = array();

	/**
	 * @param ElasticSearcher $elasticSearcher
	 */
	public function __construct(ElasticSearcher $elasticSearcher)
	{
		$this->elasticSearcher = $elasticSearcher;
	}

	/**
	 * @return Abstracts\IndexAbstract
	 *
	 * @param string                  $reference
	 * @param Abstracts\IndexAbstract $index
	 */
	public function register($reference, Abstracts\IndexAbstract $index)
	{
		$this->indices[$reference] = $index;

		return $index;
	}

	/**
	 * @param array $indices
	 */
	public function registerIndices(array $indices)
	{
		foreach ($indices as $reference => $index) {
			$this->register($reference, $index);
		}
	}

	/**
	 * @return Abstracts\IndexAbstract
	 *
	 * @param string $reference
	 */
	public function unregister($reference)
	{
		if ($this->isRegistered($reference)) {
			unset($this->indices[$reference]);
		}
	}

	/**
	 * @return bool
	 *
	 * @param string $reference
	 */
	public function isRegistered($reference)
	{
		return array_key_exists($reference, $this->indices);
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
	 * @param string $reference
	 */
	public function create($reference)
	{
		if ($this->isRegistered($reference)) {
			$index = $this->indices[$reference];

			$params = [
				'index' => $index->getName(),
				'body'  => $index->getBody()
			];

			$this->elasticSearcher->getClient()->indices()->create($params);
		}
	}

	/**
	 * @param string $reference
	 */
	public function delete($reference)
	{
		if ($this->isRegistered($reference)) {
			$index = $this->indices[$reference];

			$params = [
				'index' => $index->getName()
			];

			$this->elasticSearcher->getClient()->indices()->delete($params);
		}
	}

	/**
	 * @param string $reference
	 * @param string $type
	 */
	public function deleteType($reference, $type)
	{
		if ($this->isRegistered($reference)) {
			$index = $this->indices[$reference];

			$params = [
				'index' => $index->getName(),
				'type'  => $type
			];

			$this->elasticSearcher->getClient()->indices()->deleteMapping($params);
		}
	}

	/**
	 * @param Abstracts\IndexAbstract $index
	 * @param array                   $data
	 */
	public function index(Abstracts\IndexAbstract $index, array $data)
	{
		$params['index'] = $index->getName();
		$params['type']  = $index->getType();
		$params['body']  = $data;
		// We need an ID, if provided use that, otherwise create one based on the data.
		$params['id'] = array_key_exists('id', $data) ? $data['id'] : sha1($data);

		$this->client->index($params);
	}
}
