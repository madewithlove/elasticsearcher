<?php

namespace ElasticSearcher\Filters;

use ElasticSearcher\Abstracts\FilterAbstract;

/**
 * Simple term filter.
 *
 * @see http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/query-dsl-term-filter.html
 * @package ElasticSearcher\Filters
 */
class TermFilter extends FilterAbstract
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
