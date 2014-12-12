<?php

require '../tests/bootstrap.php';

use ElasticSearcher\Environment;
use ElasticSearcher\ElasticSearcher;

$env      = new Environment(
	['hosts' => [ELASTICSEARCH_HOST]]
);
$searcher = new ElasticSearcher($env);

// Register indexes.
$moviesIndex = new MoviesIndex();
$searcher->indicesManager()->register($moviesIndex);
