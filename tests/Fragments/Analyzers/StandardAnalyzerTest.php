<?php

use ElasticSearcher\Fragments\Analyzers\StandardAnalyzer;
use PHPUnit\Framework\TestCase;

class StandardAnalyzerTest extends TestCase
{
	public function testSimple()
	{
		$analyzer = new StandardAnalyzer('myAnalyzer');

		$this->assertEquals(['myAnalyzer' => ['type' => 'standard']], $analyzer->getBody());
	}

	public function testWithSettings()
	{
		$analyzer = new StandardAnalyzer('myAnalyzer', ['this', 'a'], 1234);

		$this->assertEquals([
			'myAnalyzer' => [
				'type' => 'standard',
				'stopwords' => ['this', 'a'],
				'max_token_length' => 1234
			]
		], $analyzer->getBody());
	}
}
