<?php

namespace Mile23\Command;

use Goutte\Client;
use Mile23\UrlBuilder;
use Mile23\Sitemap\SitemapCrawler;
use Mile23\Sitemap\HtmlCrawler;
use Pimple\Container;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;

class VerifyCommand extends Command {

  /**
   * Our logger.
   *
   * @var \Psr\Log\LoggerInterface
   *
   * @todo: Determine a t-logger strategy so we can have arbitrary loggers and
   *   still deliver a report back to the CLI.
   */
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
      ->setName('sitemap:verify')
      ->setDescription('Verify a sitemap for a file.')
      ->addArgument('baseurl', InputArgument::REQUIRED, 'Base URL of the site. No trailing slash.')
      ->addOption('timeout', 't', InputOption::VALUE_REQUIRED, 'Timeout for each page request, in seconds.', 10)
      ->addOption('spider', 's', InputOption::VALUE_NONE, 'If set, will spider out to check links on every page.');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    // @todo: Determine better exit code.
    $exit_code = 99;

    $base_url = $input->getArgument('baseurl');

    $sitemap_url = new UrlBuilder('/sitemap.xml', $base_url);

    $output->writeln('Crawling: ' . $sitemap_url);
    $sitemap = new SitemapCrawler($sitemap_url, $this->logger);

    $bad_urls = [];

    $p = new ProgressBar($output, count($sitemap));
    $p->start();
    $client = new Client();
    $client->getClient()
      ->setDefaultOption('config/curl/' . CURLOPT_TIMEOUT, $input->getOption('timeout'));
    // Pull in all URLs from the sitemap file(s), and compile a list of linked
    // URLs to check.
    // Linked array has the URL as key and NULL as the value, to be filled in
    // later with the status code of a HEAD request.
    // $linked is keyed by the URL so that we don't have duplicates.
    $linked = array();
    foreach ($sitemap as $page_url) {
//      \sleep(2);
      $crawler = $client->request('GET', $page_url);
      $status = $client->getResponse()->getStatus();
      if ($status != 200) {
        $bad_urls[] = $page_url;
      }
      else {
        if ($input->getOption('spider')) {
          $page_crawler = new HtmlCrawler($crawler, new UrlBuilder('', $base_url));
          foreach($page_crawler as $page_crawl_url => $page_crawl) {
            $linked[$page_crawl_url] = NULL;
          }
        }
      }
      $p->advance();
    }
    $p->finish();

    if ($input->getOption('spider')) {
      $linked_urls = [];
      $output->writeln('');
      $output->writeln('Spidering links...');
      $p = new ProgressBar($output, count($linked));
      $p->start();
      // Verify all linked URLs.
      foreach($linked as $resource_url => $foo) {
        try {
          $crawler = $client->request('HEAD', $resource_url);
          $status = $client->getResponse()->getStatus();
          if ($status < 400) {
            $linked_urls[$resource_url] = $client->getResponse()->getStatus();
          }
          else {
            $bad_urls[] = $resource_url;
          }
        }
        // @todo: change this to a more specific exception from Guzzle.
        catch (\Exception $e) {
          $bad_urls[] = $resource_url;
        }
        $p->advance();
      }
      $p->finish();
    }

    $output->writeln('');
    if (empty($bad_urls)) {
      $output->writeln('No errors for any page in ' . $sitemap_url);
      $exit_code = 0;
    }
    else {
      foreach ($bad_urls as $item) {
        $output->writeln($item);
      }
    }
    $output->writeln('');
    $output->writeln('<info>Done.</info>');

    return $exit_code;
  }

}
