<?php

namespace Mile23\Asset;

use Mile23\Client\ClientInterface;
use Mile23\Asset\Asset;

class AssetVisitor {

  /**
   *
   * @var \Mile23\Client\ClientInterface
   */
  protected $client;

  /**
   *
   * @var Mile23\Asset\Asset[]
   */
  protected $assets = [];

  /**
   *
   * @var Mile23\Asset\Asset[]
   */
  protected $badAssets = [];

  public function __construct(ClientInterface $client) {
    $this->client = $client;
  }

  public function addAsset(Asset $asset) {
    $url = $asset->getUrl();
    if (isset($this->assets[$url])) {
      $this->assets[$url]->addSources($asset->getSources());
    }
    else {
      $this->assets[$url] = $asset;
    }
    return $this;
  }

  protected function addBadAsset(Asset $asset) {
    $url = $asset->getUrl();
    if (isset($this->badAssets[$url])) {
      $this->badAssets[$url]->addSources($asset->getSources());
    }
    else {
      $this->badAssets[$url] = $asset;
    }
    return $this;
  }

  public function visitAsset(Asset $asset) {
    $response_code = $this->client->getResponseCode($asset->getUrl());
    if ($response_code >= 400) {
      error_log('it was bad');
      $this->addBadAsset($asset);
    }
    return $this;
  }

  public function visitAssets() {
    foreach ($this->assets as $asset) {
      $this->visitAsset($asset);
    }
    return $this;
  }

  public function getBadAssets() {
    return $this->badAssets;
  }

}
