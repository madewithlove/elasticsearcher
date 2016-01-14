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
		$sortFields = [];
		foreach ($fields as $field => $value) {
			// Simplest form, just a field name.
			if (is_numeric($field) && !is_array($value)) {
				$sortFields[] = $value;
			} // Field with direction and/or other options.
			else {
				$sortFields[] = [$field => $value];
			}
		}

		$this->set('sort', $sortFields);

		return $this;
	}

	/**
	 * @param string $fieldName
	 * @param string|null $direction
	 *
	 * @return $this
	 */
	public function sortBy($fieldName, $direction = null)
	{
		$predefinedFields = $this->get('sort');
		$sortingFields = [];

		// Field exists, prioritize and set direction (if any).
		if ($field = $this->findField($fieldName, $predefinedFields)) {
			// Field was defined without options.
			if (!is_array($field)) {
				if ($direction) {
					$sortingFields[] = [$fieldName => $direction];
				} else {
					$sortingFields[] = $fieldName;
				}
			} else {
				// Set direction, otherwise it will just use the default definition.
				if ($direction) {
					// Defined as {"post_date" : {"order" : "asc", "mode": "avg"}}
					if (is_array($field[$fieldName])) {
						$field[$fieldName]['order'] = $direction;
					} // Defined as {"post_date" : "asc"}
					else {
						$field[$fieldName] = $direction;
					}
				}
				$sortingFields[] = $field;
			}
		} // Field does not exist, add it.
		else {
			$sortingFields[] = [$fieldName => $direction];
		}

		// Add the predefined fields but remove the one we are sorting on.
		if ($predefinedFields) {
			foreach ($predefinedFields as $field) {
				if (!is_array($field) && $fieldName == $field) {
					continue;
				} elseif (is_array($field) && array_key_exists($fieldName, $field)) {
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
				if (!is_array($field) && $field == $fieldName) {
					return $field;
				} elseif (is_array($field) && array_key_exists($fieldName, $field)) {
					return $field;
				}
			}
		}

		return;
	}
}
