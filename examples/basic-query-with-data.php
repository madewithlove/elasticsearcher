<?php

require_once 'bootstrap.php';

if (!$searcher->indicesManager()->exists('movies')) {
	exit('Run basic.php first to create some data.');
}

$query = new MoviesFromXYearQuery($searcher);
$query->addData(['year' => 2014]);
$result = $query->run();

var_dump($result->getResults());
