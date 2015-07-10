<?php

namespace ElasticSearcher\Parsers;

use ElasticSearcher\Abstracts\AbstractFragment;

/**
 * Traverses an array and checks if there are any abstracts fragment to be replaced with their body.
 *
 * @package ElasticSearcher\Parsers
 */
class FragmentParser
{
	/**
	 * @param array $body
	 *
	 * @return array
	 */
	public function parse(array $body)
	{
		// Have we parsed something, we'll recursively keep parsing until this stays false.
		$parsed = false;

		// Replace all abstracts with their body.
		array_walk_recursive($body, function (&$item) use (&$parsed) {
			if ($item instanceof AbstractFragment) {
				$item = $item->getBody();
				$parsed = true;
			}
		});

		if ($parsed) {
			$body = $this->parse($body);
		}

		return $body;
	}
}
