<?php

use Pimple\Container;
use Mile23\Command\AllLinksCommand;
use Mile23\Command\VerifyCommand;
use Mile23\ContainerAwareInterface;
use Symfony\Component\Console\Application;

$c = new Container();

$c['command.alllinks'] = function() {
  return new AllLinksCommand();
};

$c['command.verify'] = function() {
  return new VerifyCommand();
};

$c['commands'] = function($c) {
  return array(
    $c['command.alllinks'],
    $c['command.verify'],
  );
};

$c['application'] = function($c) {
  $commands = $c['commands'];
  foreach($commands as $command) {
    if ($command instanceof ContainerAwareInterface) {
      $command->setContainer($c);
    }
  }
  $application = new Application();
  $application->addCommands($commands);
  return $application;
};

return $c;
