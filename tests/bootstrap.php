<?php

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/ElasticSearcherTestCase.php';
require __DIR__.'/dummy/Indexes/MoviesIndex.php';
require __DIR__.'/dummy/Indexes/AuthorsIndex.php';
require __DIR__.'/dummy/Queries/MoviesFrom2014Query.php';
require __DIR__.'/dummy/Queries/MoviesFromXYearQuery.php';
require __DIR__.'/dummy/Queries/MovieWithIDXQuery.php';
require __DIR__.'/dummy/Queries/CountMoviesFrom2014Query.php';
require __DIR__.'/dummy/Queries/PaginatedMoviesFrom2014Query.php';
require __DIR__.'/dummy/Queries/SortedQuery.php';
require __DIR__.'/dummy/Fragments/Filters/IDFilter.php';

// Test host (should be inside the vagrant box).
define('ELASTICSEARCH_HOST', 'localhost:9200');
