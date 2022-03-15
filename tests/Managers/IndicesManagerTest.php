<?php

use ElasticSearcher\Managers\IndicesManager;
use ElasticSearcher\Dummy\Indexes\MoviesIndex;
use ElasticSearcher\Dummy\Indexes\BooksIndex;

class IndicesManagerTest extends ElasticSearcherTestCase
{
	/**
	 * @var IndicesManager
	 */
	private $indicesManager;

	protected function setUp(): void
	{
		parent::setUp();

		// Create our example index.
		$this->indicesManager = $this->getElasticSearcher()->indicesManager();
		$this->indicesManager->register(new MoviesIndex());
		if ($this->indicesManager->exists('movies')) {
			$this->indicesManager->delete('movies');
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
		$this->indicesManager->create('movies');

		$this->assertTrue($this->indicesManager->exists('movies'));
	}

    public function testUpdating()
    {
        $this->indicesManager->create('movies');
        $this->indicesManager->update('movies');
    }

	public function testGetting()
	{
		$this->indicesManager->create('movies');
		$moviesIndex = new MoviesIndex();

		$this->assertArrayHasKey('movies', $this->indicesManager->indices());

        $expectedIndex = ['movies' => ['mappings' => $moviesIndex->getMappings()]];
        $this->assertEquals($expectedIndex, $this->indicesManager->get('movies'));	}

	public function testDeleting()
	{
		$this->indicesManager->create('movies');
		$this->indicesManager->delete('movies');

		$this->assertFalse($this->indicesManager->exists('movies'));
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

		$expectedIndex = ['prefix_books' => ['mappings' => $booksIndex->getMappings()]];
		$this->assertEquals($expectedIndex, $this->indicesManager->get('books'));

		$this->indicesManager->delete('books');
		$this->assertFalse($this->indicesManager->exists('books'));
	}
}
