<?php

namespace ElasticSearcher\Fragments\Filters;

use ElasticSearcher\Abstracts\AbstractFragment;

/**
 * Simple terms filter.
 *
 * @see     http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/query-dsl-terms-filter.html
 * @package ElasticSearcher\Fragments\Filters
 */
class TermsFilter extends AbstractFragment
{
	/**
	 * @param string $field
	 * @param array  $values
	 */
	public function __construct($field, array $values)
	{
		$body = [
			'terms' => [
				$field => $values
			]
		];

		$this->setBody($body);
	}
}
