<?php

/**
 * @file
 * Scrape all the URLs from a page of HTML.
 */

namespace Mile23\Sitemap;

use Goutte\Client;
use Pimple\Container;
use Mile23\UrlBuilder;
use Symfony\Component\Console\Helper\ProgressBar;

class HtmlCrawler extends \ArrayIterator {

  /**
   * Constructor method.
   *
   * @param Container $c
   *   The container for this app.
   * @param UrlBuilder $page
   *   URL builder object from which we can derive the URL of the page we want
   *   to scrape.
   */
  public function __construct(UrlBuilder $page, UrlBuilder $base_url) {
    $pages = array();

    $client = new Client();
    $crawler = $client->request('GET', (string) $page);

    $externals = array();
    $parse_crawler = $crawler->filter('a, link, script, img');

    foreach ($parse_crawler as $element) {
      switch ($element->tagName) {
        case 'a':
        case 'link':
          $attr = 'href';
          break;

        case 'script':
        case 'img':
          $attr = 'src';
      }
      $link_on_the_page = $element->getAttribute($attr);
      if ($link_on_the_page) {
        $url = new UrlBuilder($link_on_the_page, $base_url);
        // Key is URL without fragment.
        $url_key = $url->toUrl(FALSE);
        $externals[$url_key] = $url;
      }
    }

    parent::__construct($externals);
  }

}
