<?php

namespace Mile23\Sitemap;

use Goutte\Client;
use Mile23\UrlBuilder;
use Psr\Log\LoggerInterface;
use Symfony\Component\CssSelector\CssSelector;

/**
 * Get all the URLs reachable from the site's sitemap.xml.
 *
 * We're only concerned with URLs presented in the sitemap, not the HTML
 * spidering.
 */
class Sitemap extends \ArrayIterator {

  public function __construct(UrlBuilder $u, LoggerInterface $logger) {
    // The sitemaps we'll add.
    $sitemaps = [];

    // Disable `HTML` extension of CssSelector.
    CssSelector::disableHtmlExtension();
    $client = new Client();
    $crawler = $client->request('GET', (string) $u);
    $status = $client->getResponse()->getStatus();
    if ($status > 399) {
      $logger->emergency('Status ' . $status . ' getting ' . (string) $u);
    }

    // Query for the sitemap index locations.
    $sitemap_crawler = $crawler->filter('sitemapindex > sitemap > loc');

    foreach ($sitemap_crawler as $url_loc) {
      $url = $url_loc->nodeValue;
      $sitemaps[$url] = $url;
    }

    // If we didn't get any sitemaps from this index file, then it's not really
    // an index file. Add it to the list of sitemap files.
    if (empty($sitemaps)) {
      $sitemaps[(string) $u] = (string) $u;
    }

    $pages = [];
    foreach($sitemaps as $sitemap) {
      
    }

    parent::__construct($pages);
  }

}
