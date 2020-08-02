<?php

namespace Mile23\Asset;

/**
 * Data class for 'assets' that can be on an HTML page.
 */
class Asset {

  /**
   * The fully-qualified URL for this asset.
   *
   * @var string
   */
  protected $url;

  /**
   * Array of fully-qualified URLs to HTML pages that require this asset.
   *
   * @var string[]
   */
  protected $sources = [];

  /**
   * @param string $fqUrl
   *   Fully-qualified URL.
   */
  public function __construct($fqUrl, $fqSource) {
    $this->url = $fqUrl;
    $this->addSource($fqSource);
  }

  public function getUrl() {
    return $this->url;
  }

  public function addSource($fqUrl) {
    $this->sources[$fqUrl] = $fqUrl;
  }

  public function addSources($fqUrls) {
    $this->sources = array_merge($this->sources, $fqUrls);
  }

  public function getSources() {
    return $this->sources;
  }

  public function __toString() {
    return $this->url . ' from ' . count($this->sources) . ' sources.';
  }

}
