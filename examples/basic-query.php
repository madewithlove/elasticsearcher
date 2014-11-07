<?php

require_once 'bootstrap.php';

if (!$searcher->indicesManager()->exists('movies')) {
	exit('Run basic.php first to create some data.');
}

$query = new MoviesFrom2014Query($searcher);
$result = $query->run();

var_dump($result->getResults());
