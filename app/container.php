<?php

use Pimple\Container;
use Mile23\SitemapLogger;
use Mile23\Command\VerifyCommand;
use Symfony\Component\Console\Application;

$c = new Container();

$c['logger'] = $c->factory(function () {
  return new SitemapLogger();
});

$c['command.verify'] = $c->factory(function($c) {
  return VerifyCommand::create($c);
});

$c['commands'] = $c->factory(function($c) {
  return array(
    $c['command.verify'],
  );
});

$c['application'] = function($c) {
  $commands = $c['commands'];
  $application = new Application('Sitemap Crawler', '0.0.1-alpha1');
  $application->addCommands($commands);
  return $application;
};

return $c;
