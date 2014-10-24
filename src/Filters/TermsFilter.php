<?php

namespace ElasticSearcher\Filters;

use ElasticSearcher\Abstracts\FilterAbstract;

/**
 * Simple terms filter.
 *
 * @see http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/query-dsl-terms-filter.html
 * @package ElasticSearcher\Filters
 */
class TermsFilter extends FilterAbstract
{
	/**
	 * @param string $field
	 * @param array $values
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
