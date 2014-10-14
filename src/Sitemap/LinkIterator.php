<?php

/**
 * @file
 * Given some HTML, extract all references to external resources.
 */

namespace Mile23\Sitemap;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Iterate over HTML and extract all links.
 *
 * key() returns a URI.
 * current() can return a fragment, such as '#anchor'.
 */
class LinkIterator implements \Iterator {

  protected $crawler;

  // Array of strings.
  protected $links;


  public function __construct(Crawler $crawler) {
    $links = array();
    $this->crawler = $crawler;
    $href_crawl = $crawler->filter('a, link')->extract(array('href'));
    foreach($href_crawl as $url) {
      $links[] = $url;
    }

  }

  public function current() {

  }

  public function key() {

  }

  public function next() {

  }

  public function rewind() {

  }

  public function valid() {
    return TRUE;
  }

}
