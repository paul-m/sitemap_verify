<?php

/**
 * @file
 * Scrape all the URLs from a page of HTML.
 */

namespace Mile23\Sitemap;

use Goutte\Client;
use Pimple\Container;
use Mile23\UrlBuilder;

class HtmlCrawler extends \ArrayIterator {

  protected $container;
  // Url object for the sitemap we're examining.
  protected $url;

  /**
   * Constructor method.
   *
   * @param Container $c
   *   The container for this app.
   * @param UrlBuilder $u
   *   URL builder object from which we can derive the URL of the page we want
   *   to scrape.
   */
  public function __construct(Container $c, UrlBuilder $u) {
    $pages = array();
    $this->container = $c;
    $this->url = $u;
    $base_url = $c['command.alllinks.baseurl'];
    $output = $c['command.output'];

    $client = new Client();
    $crawler = $client->request('GET', (string) $u);

    $externals = array();

    foreach ($crawler->filter('a, link, script, img') as $element) {
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
