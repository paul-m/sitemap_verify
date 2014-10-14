<?php

namespace Mile23;

use Pimple\Container;

interface ContainerAwareInterface {
  public function setContainer(Container $c);
}
