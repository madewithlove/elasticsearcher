<?php

use ElasticSearcher\Managers\IndicesManager;
use ElasticSearcher\Dummy\Indexes\AuthorsIndex;
use ElasticSearcher\Dummy\Indexes\MoviesIndex;
use ElasticSearcher\Dummy\Indexes\BooksIndex;

class IndicesManagerTest extends ElasticSearcherTestCase
{
	/**
	 * @var IndicesManager
	 */
	private $indicesManager;

	public function setUp()
	{
		parent::setUp();

		// Create our example index.
		$this->indicesManager = $this->getElasticSearcher()->indicesManager();
		$this->indicesManager->register(new AuthorsIndex());
		if ($this->indicesManager->exists('authors')) {
			$this->indicesManager->delete('authors');
		}
	}

	public function testRegister()
	{
		// New instance so we have an empty register.
		$indicesManager = new IndicesManager($this->getElasticSearcher());
		$moviesIndex    = new MoviesIndex();

		// Single registration.
		$indicesManager->register($moviesIndex);
		$this->assertEquals(true, $indicesManager->isRegistered('movies'));
		$this->assertArrayHasKey('movies', $indicesManager->registeredIndices());
		$this->assertInstanceOf(MoviesIndex::class, $indicesManager->getRegistered('movies'));

		// Removing from register.
		$indicesManager->unregister('movies');
		$this->assertEquals(false, $indicesManager->isRegistered('movies'));
		$this->assertArrayNotHasKey('movies', $indicesManager->registeredIndices());

		// Bulk registering.
		$indicesManager->registerIndices([$moviesIndex]);
		$this->assertEquals(true, $indicesManager->isRegistered('movies'));
	}

	public function testCreating()
	{
		$this->indicesManager->create('authors');

		$this->assertTrue($this->indicesManager->exists('authors'));
		$this->assertTrue($this->indicesManager->existsType('authors', 'directors'));
		$this->assertTrue($this->indicesManager->existsType('authors', 'producers'));
	}

    public function testUpdating()
    {
        $this->indicesManager->create('authors');
        $this->indicesManager->update('authors');
    }

	public function testGetting()
	{
		$this->indicesManager->create('authors');
		$authorsIndex = new AuthorsIndex();

		$this->assertArrayHasKey('authors', $this->indicesManager->indices());

		$expectedIndex = ['authors' => ['mappings' => $authorsIndex->getTypes()]];
		$this->assertEquals($expectedIndex, $this->indicesManager->get('authors'));

		$expectedIndex = [
			'authors' => [
			    'mappings' => array_intersect_key($authorsIndex->getTypes(), array_flip((array) 'producers')),
            ],
		];
		$this->assertEquals($expectedIndex, $this->indicesManager->getType('authors', 'producers'));
	}

	public function testDeleting()
	{
		$this->indicesManager->create('authors');
		$this->indicesManager->delete('authors');

		$this->assertFalse($this->indicesManager->exists('authors'));
	}

	public function testWithPrefixedIndex()
	{
		$booksIndex = new BooksIndex();
		$this->indicesManager->register($booksIndex);
		if ($this->indicesManager->exists('books')) {
			$this->indicesManager->delete('books');
		}

		$this->indicesManager->create('books');
		$this->assertTrue($this->indicesManager->exists('books'));

		$expectedIndex = ['prefix_books' => ['mappings' => $booksIndex->getTypes()]];
		$this->assertEquals($expectedIndex, $this->indicesManager->get('books'));

		$this->indicesManager->delete('books');
		$this->assertFalse($this->indicesManager->exists('books'));
	}
}
