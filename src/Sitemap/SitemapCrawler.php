<?php

namespace Mile23\Sitemap;

use Mile23\UrlBuilder;
use Psr\Log\LoggerInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;

class SitemapCrawler extends \ArrayIterator {

  /**
   *
   * @param UrlBuilder $u
   * @param LoggerInterface $logger
   * @throws \RuntimeException
   *
   * @todo Use the logger.
   */
  public function __construct(UrlBuilder $u, LoggerInterface $logger) {
    $pages = array();

    $browser = new HttpBrowser(HttpClient::create());

    $browser->request('GET', (string) $u);
    $status = $browser->getInternalResponse()->getStatusCode();
    if ($status > 399) {
      throw new \RuntimeException('STATUS: ' . $status . ' getting ' . (string) $u);
    }

    $sitemap_crawler = $browser->getCrawler()->filter('urlset > url > loc');
    foreach ($sitemap_crawler as $url_loc) {
      $url = $url_loc->nodeValue;
      $pages[$url] = $url;
    }

    parent::__construct($pages);
  }

}
