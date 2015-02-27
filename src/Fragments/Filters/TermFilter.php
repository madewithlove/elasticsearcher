<?php

namespace ElasticSearcher\Fragments\Filters;

use ElasticSearcher\Abstracts\AbstractFragment;

/**
 * Simple term filter.
 *
 * @see     http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/query-dsl-term-filter.html
 * @package ElasticSearcher\Fragments\Filters
 */
class TermFilter extends AbstractFragment
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
