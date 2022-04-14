<?php

use ElasticSearcher\Environment;
use ElasticSearcher\ElasticSearcher;
use PHPUnit\Framework\TestCase;

/**
 * ElasticSearcher specific test case handling.
 * Will do some global setup/teardown.
 */
class ElasticSearcherTestCase extends TestCase
{
	/**
	 * @var ElasticSearcher
	 */
	private $elasticSearcher;

	/**
	 * Create a test environment.
	 */
	protected function setUp(): void
	{
		$env = new Environment(
			['hosts' => [ELASTICSEARCH_HOST]]
		);
		$this->elasticSearcher = new ElasticSearcher($env);
	}

	/**
	 * @return ElasticSearcher
	 */
	public function getElasticSearcher()
	{
		return $this->elasticSearcher;
	}
}
