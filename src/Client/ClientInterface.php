<?php

namespace Mile23\Client;

interface ClientInterface {

  /**
   * Get the headers for a URL using a HEAD request.
   *
   * @param string $url
   *   The URL.
   *
   * @return string[]|bool
   *   Array of headers, or FALSE on error.
   *
   * @see get_headers()
   */
  public function getHeaders($url);

  /**
   *
   * @param string $url
   * @return int
   */
  public function getResponseCode($url);
}
