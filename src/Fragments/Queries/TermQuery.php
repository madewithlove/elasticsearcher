<?php

namespace ElasticSearcher\Fragments\Queries;

use ElasticSearcher\Abstracts\AbstractFragment;

/**
 * Simple term query.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-term-query.html
 * @package ElasticSearcher\Fragments\Queries
 */
class TermQuery extends AbstractFragment
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
