<?php

namespace Mile23\Client;

use GuzzleHttp\Client as GuzzleClient;

class Client implements ClientInterface {

  /**
   *
   * @var \GuzzleHttp\Client
   */
  protected $client;

  public function __construct() {
    $this->client = new GuzzleClient(['http_errors' => FALSE]);
  }

  /**
   * {@inheritdoc}
   */
  public function getResponseCode($url) {
    return $this->client->head($url)->getStatusCode();
  }

  public function getHeaders($url) {
    $response = $this->client->head($url);
    return $response->getHeaders();
  }

}
