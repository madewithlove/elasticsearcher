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
	 * @param array|AbstractFragment $body
	 *
	 * @return array
	 */
	public function parse($body)
	{
		if (is_array($body)) {
			foreach ($body as $key => &$item) {
				if ($item instanceof AbstractFragment) {
					$parsedBody = $this->parse($item->getBody());

					// Add the parsed fragment to the parent.
					if ($item->mergeWithParent) {
						$body = array_merge($body, $parsedBody);
						unset($body[$key]);
					} // Replace its current position with the parsed fragment.
					else {
						$item = $parsedBody;
					}
				} // Further nesting to be parsed.
				elseif (is_array($item)) {
					$item = $this->parse($item);
				}
			}
			// Root level fragment, does not have a parent so it can not be merged with one.
		} elseif ($body instanceof AbstractFragment) {
			$body = $this->parse($body->getBody());
		}

		return $body;
	}
}
