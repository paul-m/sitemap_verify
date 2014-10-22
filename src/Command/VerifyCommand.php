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
use Symfony\Component\Console\Helper\ProgressBar;

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
    $c['command.alllinks.baseurl'] = $base_url;

    $url = new UrlBuilder('/sitemap.xml', $base_url);

    $output->writeln('Crawling: ' . $url);

    $sitemap = new SitemapCrawler($url);

    $resources = array();

    $p = new ProgressBar($output, count($sitemap));
    $p->setMessage('Crawling...');
    $p->start();

    /*    foreach($sitemap as $page) {
      $page_crawler = new HtmlCrawler(new UrlBuilder($page), $url);
      $resources[(string) $page] = $page_crawler;
      $p->advance();
      \sleep(5);
      } */

    // For now, only request HEAD on URLs.
    $client = new Client();
    $client->getClient()->setDefaultOption('config/curl/' . CURLOPT_TIMEOUT, 2);
    foreach ($sitemap as $page_url) {

//      \sleep(2);
      $crawler = $client->request('HEAD', $page_url);

      $status = $client->getResponse()->getStatus();
      if ($status != 200) {
        $resources[] = $page_url;
      }
      $p->advance();
    }

    $p->finish();

    foreach ($resources as $item) {
      $output->writeln($item);
    }
  }

  public function setContainer(Container $c) {
    $this->container = $c;
  }

}
