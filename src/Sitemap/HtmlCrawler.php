<?php

/**
 * @file
 * Scrape all the URLs from a page of HTML.
 */

namespace Mile23\Sitemap;

use Mile23\UrlBuilder;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Construct an array of linked items on the page which can be checked for
 * availability.
 */
class HtmlCrawler extends \ArrayIterator {

  /**
   * Constructor method.
   *
   * @param Crawler $crawler
   *   A crawler object which already contains info about the page we're
   *   scraping.
   * @param UrlBuilder $page
   *   URL builder object from which we can derive the URL of the page we want
   *   to scrape.
   */
  public function __construct(Crawler $crawler, UrlBuilder $base_url) {
    $pages = array();

    $externals = array();
    $parse_crawler = $crawler->filter('a, link, script, img');

    foreach ($parse_crawler as $element) {
      $attr = '';
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
