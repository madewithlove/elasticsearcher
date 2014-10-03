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
	 * Update the index and all its types. This should be used when wanting to reflect changes
	 * in the Index object with the elasticsearch server.
	 *
	 * @param string $reference
	 */
	public function update($reference)
	{
		if ($this->isRegistered($reference)) {
			$index = $this->indices[$reference];

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
	 * @return bool
	 *
	 * @param string $reference
	 */
	public function exists($reference)
	{
		if ($this->isRegistered($reference)) {
			$index = $this->indices[$reference];

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
	 * @param string $reference
	 * @param string $type
	 */
	public function existsType($reference, $type)
	{
		if ($this->isRegistered($reference)) {
			$index = $this->indices[$reference];

			$params = [
				'index' => $index->getName(),
				'type'  => $type
			];

			return $this->elasticSearcher->getClient()->indices()->existsType($params);
		}

		return false;
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
		if ($this->isRegistered($reference)) {
			$index = $this->indices[$reference];

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
