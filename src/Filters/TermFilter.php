<?php

namespace ElasticSearcher\Filters;

use ElasticSearcher\Abstracts\AbstractFilter;

/**
 * Simple term filter.
 *
 * @see http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/query-dsl-term-filter.html
 * @package ElasticSearcher\Filters
 */
class TermFilter extends AbstractFilter
{
	/**
	 * @param string $field
	 * @param string $value
	 */
	public function __construct($field, $value)
	{
		$body = [
			'term' => [
				$field => $value
			]
		];

		$this->setBody($body);
	}
}
