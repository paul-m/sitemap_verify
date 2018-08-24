<?php

namespace Mile23\Sitemap;

use Goutte\Client;
use Mile23\UrlBuilder;
use Psr\Log\LoggerInterface;
use Symfony\Component\CssSelector\CssSelector;

class SitemapCrawler extends \ArrayIterator {

  public function __construct(UrlBuilder $u, LoggerInterface $logger) {
    $pages = array();

    // Disable `HTML` extension of CssSelector.
    CssSelector::disableHtmlExtension();
    $client = new Client();
    $crawler = $client->request('GET', (string) $u);
    $status = $client->getResponse()->getStatus();
    if ($status > 399) {
      throw new \RuntimeException('STATUS: ' . $status . ' getting ' . (string) $u);
    }

    $sitemap_crawler = $crawler->filter('urlset > url > loc');

    foreach ($sitemap_crawler as $url_loc) {
      $url = $url_loc->nodeValue;
      $pages[$url] = $url;
    }

    parent::__construct($pages);
  }

}
