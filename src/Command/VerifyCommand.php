<?php

namespace Mile23\Command;

use Goutte\Client;
use Mile23\ContainerAwareInterface;
use Mile23\UrlBuilder;
use Mile23\Sitemap\SitemapCrawler;
use Pimple\Container;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class VerifyCommand extends Command implements ContainerAwareInterface {

  protected $container;

  protected function configure() {
    $this
      ->setName('sitemap:verify')
      ->setDescription('Verify a sitemap for a file.')
      ->addArgument(
        'baseurl', InputArgument::REQUIRED, 'Base URL of the site. No trailing slash.'
    );
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $base_url = $input->getArgument('baseurl');
    $c = $this->container;
    $c['command.output'] = $output;
    $c['command.verify.baseurl'] = $base_url;

    $url = new UrlBuilder('/sitemap.xml', $base_url);

    $sitemap = new SitemapCrawler($c, $url);

    foreach($sitemap as $site) {
      $output->writeln($site);
    }

  }

  public function setContainer(Container $c) {
    $this->container = $c;
  }

}
