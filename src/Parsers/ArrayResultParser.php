<?php

namespace ElasticSearcher\Parsers;

use ElasticSearcher\Abstracts\AbstractResultParser;

/**
 * @package ElasticSearcher\Parsers
 */
class ArrayResultParser extends AbstractResultParser
{
	public function getResults()
	{
		return $this->getHits();
	}
}
