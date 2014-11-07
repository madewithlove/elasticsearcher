<?php

require '../vendor/autoload.php';
require 'Indexes/MoviesIndex.php';
require 'Queries/MoviesFrom2014Query.php';
require 'Queries/MoviesFromXYearQuery.php';

use ElasticSearcher\Environment;
use ElasticSearcher\ElasticSearcher;

$env      = new Environment(
	['hosts' => ['localhost:9200']]
);
$searcher = new ElasticSearcher($env);

// Register indexes.
$moviesIndex = new MoviesIndex();
$searcher->indicesManager()->register($moviesIndex);
