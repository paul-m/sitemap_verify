<?php

namespace Mile23\Sitemap;

use Goutte\Client;
use Mile23\UrlBuilder;
use Psr\Log\LoggerInterface;
use Symfony\Component\CssSelector\CssSelector;

/**
 * Generate a list of sitemaps to crawl based on the assumption that this is an index.
 */
class SitemapIndexCrawler extends \ArrayIterator {

  protected $client;

  public function getClient() {
    return $this->client;
  }

  public function __construct(UrlBuilder $u, LoggerInterface $logger) {
    // The sitemaps we'll add.
    $sitemaps = [];

    // Disable `HTML` extension of CssSelector.
    CssSelector::disableHtmlExtension();
    $this->client = new Client();
    $crawler = $this->client->request('GET', (string) $u);
    $status = $this->client->getResponse()->getStatus();
    if ($status > 399) {
      $logger->emergency('Status ' . $status . ' getting ' . (string) $u);
      throw new \Exception((string) $u . ' is not a sitemap index file.');
    }

    // Query for the sitemap index locations.
    $sitemap_crawler = $crawler->filter('sitemapindex > sitemap > loc');
    // If we didn't get any sitemaps from this index file, then it's not really
    // an index file.
    if (empty($sitemaps)) {
      throw new \LogicException((string) $u . ' is not a sitemap index file.');
    }

    foreach ($sitemap_crawler as $url_loc) {
      $url = $url_loc->nodeValue;
      $sitemaps[$url] = $url;
    }

    $pages = [];
    foreach($sitemaps as $sitemap) {

    }

    parent::__construct($pages);
  }

}
