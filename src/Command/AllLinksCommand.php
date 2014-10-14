<?php

namespace Mile23\Command;

use Mile23\UrlBuilder;
use Mile23\ContainerAwareInterface;
use Goutte\Client;
use Pimple\Container;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AllLinksCommand extends Command implements ContainerAwareInterface {

  protected $container;

  protected function configure() {
    $this
      ->setName('page:links')
      ->setDescription('Show all the links on an HTML page.')
      ->addArgument(
        'uri', InputArgument::REQUIRED, 'URI of the page. No trailing slash.'
      )
      ->addArgument(
        'baseurl', InputArgument::REQUIRED, 'Base URL for the whole site. Required to verify URL fragments'
    );
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $uri = $input->getArgument('uri');
    $base_url = $input->getArgument('baseurl');

    $client = new Client();
    $crawler = $client->request('GET', $uri);

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
        $output->writeln((string) $url);
      }
    }
    if (!empty($this->container)) {
      $output->writeln('Woot container!');
    }
  }

  public function setContainer(Container $c) {
    $this->container = $c;
  }

}
