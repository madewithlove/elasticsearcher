<?php

namespace ElasticSearcher\Fragments\Analyzers;

use ElasticSearcher\Abstracts\AbstractFragment;

/**
 * Standard analyzer.
 *
 * @see     https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-standard-analyzer.html
 * @package ElasticSearcher\Fragments\Analyzers
 */
class StandardAnalyzer extends AbstractFragment
{
	/**
	 * @var bool
	 */
	public $mergeWithParent = true;

	/**
	 * @param string $name
	 * @param null|array $stopwords
	 * @param null|integer $maxTokenLength
	 */
	public function __construct($name, $stopwords = null, $maxTokenLength = null)
	{
		$settings = [
			'type' => 'standard'
		];
		if ($stopwords) {
			$settings['stopwords'] = (array) $stopwords;
		}
		if ($maxTokenLength) {
			$settings['max_token_length'] = (int) $maxTokenLength;
		}

		$body = [
			$name => $settings
		];

		$this->setBody($body);
	}
}
