<?php

namespace ElasticSearcher\Dummy\Indexes;

use ElasticSearcher\Abstracts\AbstractIndex;

class BooksIndex extends AbstractIndex
{
	public function getName()
	{
		return 'books';
	}

	public function getInternalName()
	{
		return 'prefix_'.$this->getName();
	}

	public function setup()
	{
		$this->setMappings([
			'properties' => [
				'id' => ['type' => 'integer'],
				'name' => ['type' => 'text'],
			]
		]);
	}
}
