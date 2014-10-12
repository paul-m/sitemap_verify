<?php

namespace Mile23\Command;

use Goutte\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class VerifyCommand extends Command {

  protected function configure() {
    $this
      ->setName('sitemap:verify')
      ->setDescription('Verify a sitemap for a file.')
      ->addArgument(
        'baseurl', InputArgument::REQUIRED, 'Base URL of the site. No trailing slash.'
      )
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $base_url = $input->getArgument('baseurl');

    $client = new Client();
    $crawler = $client->request('GET', $base_url . '/sitemap.xml');
    $text = $crawler->text();

    $output->writeln($text);
  }

}
