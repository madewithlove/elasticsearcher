<?php

namespace ElasticSearcher\Fragments\Queries;

use ElasticSearcher\Abstracts\AbstractFragment;

/**
 * Simple terms query.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-terms-query.html
 * @package ElasticSearcher\Fragments\Queries
 */
class TermsQuery extends AbstractFragment
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
