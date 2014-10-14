<?php

namespace Mile23\Sitemap;

use Goutte\Client;
use Pimple\Container;
use Mile23\UrlBuilder;

class SitemapCrawler extends \ArrayIterator {

  protected $container;
  // Url object for the sitemap we're examining.
  protected $url;

  public function __construct(Container $c, UrlBuilder $u) {
    $pages = array();
    $this->container = $c;
    $this->url = $u;
    $baseurl = $c['command.verify.baseurl'];
    $output = $c['command.output'];

    $client = new Client();
    $crawler = $client->request('GET', (string) $u);

    foreach ($crawler->filter('urlset > url > loc') as $dom_element) {
      $url = $dom_element->nodeValue;
      $pages[$url] = $url;
    }
    parent::__construct($pages);
  }

}
