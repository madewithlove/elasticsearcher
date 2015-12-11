<?php

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/ElasticSearcherTestCase.php';

// Test host (should be inside the vagrant box).
define('ELASTICSEARCH_HOST', 'localhost:9200');
