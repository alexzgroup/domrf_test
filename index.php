<?php

require 'autoloader.php';

$start = microtime(true);

$parser = new \Classes\DomRFParser();
if ($parser->init()) {
    $parser->readFile();
    $parser->parseData();

    $parser->saveData(\Classes\BaseParser::JSON_FORMAT);
    $parser->saveData(\Classes\BaseParser::XML_FORMAT);
}

$end = microtime(true);

$time_worked = microtime(true) - $start;
exit(date('c', $start) . ' - ' . date('c', $end) . ' :: script ' . __FILE__ . ' work ' . $time_worked . '  s.');
