<?php

use ElasticSearcher\Parsers\FragmentParser;
use ElasticSearcher\Fragments\Filters\TermFilter;

class FragmentParserTest extends ElasticSearcherTestCase
{
	public function testParsingRootLevel()
	{
		$parser = new FragmentParser();

		$body = [
			'query' => new TermFilter('name', 'John'),
		];
		$expectedBody = [
			'query' => [
				'term' => [
					'name' => 'John',
				]
			]
		];

		$this->assertEquals($expectedBody, $parser->parse($body));
	}

	public function testParsingChildLevel()
	{
		$parser = new FragmentParser();

		$body = [
			'query' => [
				'bool' => [
					'and' => [
						new TermFilter('name', 'John'),
						new TermFilter('category', 'authors'),
					]
				]
			]
		];
		$expectedBody = [
			'query' => [
				'bool' => [
					'and' => [
						[
							'term' => [
								'name' => 'John'
							]
						],
						[
							'term' => [
								'category' => 'authors'
							]
						],
					]
				]
			]
		];

		$this->assertEquals($expectedBody, $parser->parse($body));
	}

	public function testParsingNestedFragments()
	{
		$parser = new FragmentParser();

		$body = [
			'query' => [
				'bool' => [
					'and' => [
						new TermFilter('name', new TermFilter('category', 'authors')),
					]
				]
			]
		];
		$expectedBody = [
			'query' => [
				'bool' => [
					'and' => [
						[
							'term' => [
								'name' => [
									'term' => [
										'category' => 'authors'
									]
								]
							]
						],
					]
				]
			]
		];

		$this->assertEquals($expectedBody, $parser->parse($body));
	}
}
