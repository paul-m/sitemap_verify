<?php

/**
 * @file
 * Scrape all the URLs from a page of HTML.
 */

namespace Mile23\Crawler;

use Mile23\UrlBuilder;
use Symfony\Component\DomCrawler\Crawler;
use Mile23\Asset\AssetVisitor;
use Mile23\Client\Client;
use Mile23\Asset\Asset;

/**
 * Crawl an HTML page for assets.
 */
class HtmlAssetCrawler extends \ArrayIterator {

  /**
   * The rel attributes we care about.
   *
   * @var string[]
   *
   * @see https://html.spec.whatwg.org/multipage/links.html#body-ok
   */
  protected $linkRel = [
    'icon',
    'modulepreload',
    'pingback',
    'preconnect',
    'prefetch',
    'preload',
    'prerender',
    'stylesheet',
  ];

  /**
   *
   * @var \Mile23\Asset\AssetVisitor
   */
  protected $assetVisitor;

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
    $this->assetVisitor = new AssetVisitor(new Client());

    // Link tags.
    $parse_crawler = $crawler->filter('link');
    foreach ($parse_crawler as $element) {
      if (in_array($element->getAttribute('rel'), $this->linkRel)) {
        $this->assetVisitor->addAsset(new Asset($base_url->toUrl(TRUE), $fqSource));


      }
    }





    $externals = array();
    $parse_crawler = $crawler->filter('link, script, img');

    foreach ($parse_crawler as $element) {
      $attr = '';
      switch ($element->tagName) {
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
