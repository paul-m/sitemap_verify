<?php

namespace Mile23\Command;

use Mile23\UrlBuilder;
use Mile23\Sitemap\HtmlCrawler;
use Pimple\Container;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AllLinksCommand extends Command {

  protected $logger;

  public static function create(Container $c) {
    return new static($c['logger'], NULL);
  }

  public function __construct(LoggerInterface $logger, $name = null) {
    parent::__construct($name);
    $this->logger = $logger;
  }

  protected function configure() {
    $this
      ->setName('page:links')
      ->setDescription('Show all the links on an HTML page.')
      ->addArgument(
        'uri', InputArgument::REQUIRED, 'URI of the page. No trailing slash.'
      )
      ->addArgument(
        'baseurl', InputArgument::REQUIRED, 'Base URL for the whole site. Required to verify URL fragments'
    );
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $uri = $input->getArgument('uri');
    $base_url = $input->getArgument('baseurl');
    $c = $this->container;
    $c['command.alllinks.baseurl'] = $base_url;
    $c['command.output'] = $output;

    $links = new HtmlCrawler(
      new UrlBuilder($uri, $base_url),
      new UrlBuilder($base_url),
      $output
    );

    foreach ($links as $link) {
      $output->writeln((string) $link);
    }
  }

}
