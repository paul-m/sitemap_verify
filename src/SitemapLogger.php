<?php

namespace Mile23;

use Psr\Log\AbstractLogger;

/**
 * A logging service for sitemap spiders.
 *
 * @todo: Make this actually do something.
 */
class SitemapLogger extends AbstractLogger {

  /**
   * The actual log data. We store a chronological array.
   *
   * @var array
   */
  protected $log = [];

  /**
   * Logs with an arbitrary level.
   *
   * @param mixed $level
   * @param string $message
   * @param array $context
   * @return null
   */
  public function log($level, $message, array $context = array()) {
    $this->log[] = [$level, $message];
  }

}
