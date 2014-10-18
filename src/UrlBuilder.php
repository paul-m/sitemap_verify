<?php

namespace Mile23;

/**
 * Yet Another URL builder.
 */
class UrlBuilder {

  protected $originalFragment;
  protected $baseUrl;
  protected $parts;

  public function __construct($url_fragment = '', $base_url = '') {
    $this->originalFragment = $url_fragment;
    $this->baseUrl = $base_url;
    $this->parts = $this->emptyParts();
  }

  protected function setPart($part_name, $value) {
    $this->parts = array_merge(
      $this->parts, array($part_name => $value)
    );
  }

  private function emptyParts() {
    return array_fill_keys(
      array('scheme', 'host', 'port', 'user', 'pass', 'path', 'query', 'fragment'), ''
    );
  }

  public function setScheme($scheme) {
    $this->setPart('scheme', $scheme);
  }

  public function setHost($host) {
    $this->setPart('host', $host);
  }

  public function setPort($port) {
    $this->setPart('port', $port);
  }

  public function setUser($user) {
    $this->setPart('user', $user);
  }

  public function setPass($pass) {
    $this->setPart('pass', $pass);
  }

  public function setPath($path) {
    $this->setPart('path', $path);
  }

  public function setQuery($query) {
    $this->setPart('query', $query);
  }

  public function setFragment($fragment) {
    $this->setPart('fragment', $fragment);
  }

  public function toUrl($include_fragment = TRUE) {
    $empty_parts = $this->emptyParts();
    // Gather parts of original fragment.
    $parts = array_merge($empty_parts, parse_url($this->originalFragment));
    // Gather parts of base url.
    $base_parts = array_merge($empty_parts, parse_url($this->baseUrl));
    // Fill original parts with base url if needed, then add parts modified with
    // setter methods.
    foreach ($parts as $name => $part) {
      // Fold in base url.
      if (empty($parts[$name])) {
        $parts[$name] = $base_parts[$name];
      }
      if (!empty($this->parts[$name])) {
        $parts[$name] = $this->parts[$name];
      }
    }

    // Many hand-waving assumptions in the following code.
    $url = $parts['scheme'] . '://' . $parts['host'];
    if (!empty($parts['port'])) {
      $url .= ':' . $parts['port'];
    }
    $url .= $parts['path'];
    if (!empty($parts['query'])) {
      $url .= '?' . $parts['query'];
    }
    if ($include_fragment) {
      if (!empty($parts['fragment'])) {
        $url .= '#' . $parts['fragment'];
      }
    }
    return $url;
  }

  public function __toString() {
    return $this->toUrl(TRUE);
  }

}
