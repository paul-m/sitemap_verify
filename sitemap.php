<?php

require_once __DIR__ . '/vendor/autoload.php';

use Mile23\Command\VerifyCommand;
use Symfony\Component\Console\Application;

$app = new Application('Sitemap verifier', '0.0.1-dev');
$app->add(new VerifyCommand());
$app->run();
