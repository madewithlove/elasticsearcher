<?php
namespace ElasticSearcher\Fragments\Traits;

/**
 * Shortcut for adding sorting to a Query.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-request-sort.html
 * @package ElasticSearcher\Fragments\Traits
 */
trait SortableTrait
{
	/**
	 * @param array $fields
	 *
	 * @return $this
	 */
	public function sort(array $fields)
	{
		// Each field needs to be wrapped in an array.
		$sortFields = [];
		foreach ($fields as $field => $options) {
			$sortFields[] = [$field => $options];
		}

		$this->set('sort', $sortFields);

		return $this;
	}

	/**
	 * @param string $fieldName
	 * @param string $direction
	 *
	 * @return $this
	 */
	public function sortBy($fieldName, $direction = 'asc')
	{
		$predefinedFields = $this->get('sort');
		$sortingFields = [];

		// Field exists, just need to set the direction.
		if ($field = $this->findField($fieldName, $predefinedFields)) {
			// Defined as {"post_date" : {"order" : "asc", "mode": "avg"}}
			if (is_array($field[$fieldName])) {
				$field[$fieldName]['order'] = $direction;
			} // Defined as {"post_date" : "asc"}
			else {
				$field[$fieldName] = $direction;
			}
			$sortingFields[] = $field;
		} // Field does not exist, add it.
		else {
			$sortingFields[] = [$fieldName => $direction];
		}

		// Add the predefined fields but remove the one we are sorting on.
		if ($predefinedFields) {
			foreach ($predefinedFields as $field) {
				if (array_key_exists($fieldName, $field)) {
					continue;
				}

				$sortingFields[] = $field;
			}
		}

		$this->set('sort', $sortingFields);

		return $this;
	}

	/**
	 * @param string $fieldName
	 * @param array|null $fields
	 */
	private function findField($fieldName, $fields)
	{
		if ($fields) {
			foreach ($fields as $field) {
				if (array_key_exists($fieldName, $field)) {
					return $field;
				}
			}
		}

		return;
	}
}
