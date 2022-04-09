<?php

if (php_sapi_name() !== 'cli') {
    exit;
}

require __DIR__ . '/vendor/autoload.php';

use App\Cli;
use App\Writer\CliWriter;

$app = new Cli(new CliWriter());
$app->run($argv);
