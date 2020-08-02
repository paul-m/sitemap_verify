<?php

namespace Mile23\BrowserKit;

use Symfony\Component\DomCrawler\Crawler;

interface ClientDecoratorInterface {

  /**
   * Calls a URI.
   *
   * @param string $method        The request method
   * @param string $uri           The URI to fetch
   * @param array  $parameters    The Request parameters
   * @param array  $files         The files
   * @param array  $server        The server parameters (HTTP headers are referenced with a HTTP_ prefix as PHP does)
   * @param string $content       The raw body data
   * @param bool   $changeHistory Whether to update the history or not (only used internally for back(), forward(), and reload())
   *
   * @return \Symfony\Component\DomCrawler\Crawler
   */
  public function request(string $method, string $uri, array $parameters = [], array $files = [], array $server = [], string $content = null, bool $changeHistory = true);
}
