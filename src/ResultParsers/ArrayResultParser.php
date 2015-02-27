<?php

namespace ElasticSearcher\ResultParsers;

use ElasticSearcher\Abstracts\AbstractResultParser;

/**
 * @package ElasticSearcher\ResultParsers
 */
class ArrayResultParser extends AbstractResultParser
{
	public function getResults()
	{
		return $this->getHits();
	}
}
