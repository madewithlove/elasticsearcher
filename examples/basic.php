<?php

require_once 'bootstrap.php';

// Create in elasticsearch if it does not exist.
if (!$searcher->indicesManager()->exists('movies')) {
	$searcher->indicesManager()->create('movies');
}

// Index some data.
$data = array(
	array(
		'id' => 1,
		'name' => 'Fury',
		'year' => 2014
	),
	array(
		'id' => 2,
		'name' => 'Interstellar',
		'year' => 2014
	),
	array(
		'id' => 3,
		'name' => 'Hercules',
		'year' => 2014
	)
);
$searcher->documentsManager()->bulkIndex('movies', 'movies', $data);

// Single indexing.
$movie = array(
	'id' => 4,
	'name' => 'The Shawshank Redemption',
	'year' => 1994
);
$searcher->documentsManager()->index('movies', 'movies', $movie);
