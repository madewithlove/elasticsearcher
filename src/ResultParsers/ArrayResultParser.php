<?php

namespace ElasticSearcher\ResultParsers;

use ElasticSearcher\Abstracts\ResultParserAbstract;

/**
 * @package ElasticSearcher\ResultParsers
 */
class ArrayResultParser extends ResultParserAbstract
{
	public function getResults()
	{
		return $this->getHits();
	}
}
