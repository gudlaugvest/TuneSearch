<?php

namespace Drupal\hello_world;

use Drupal\Core\Config\ConfigFactoryInterface;

class HelloWorldSalutation {
  protected $configFactory;
  public function __construct(ConfigFactoryInterface $configFactory) {
    $this->configFactory = $config_factory;
  }
  public function getSalutation() {
    $config = $this->configFactory->get(name: "hello_world.custom_salutation");
    $salutation = $config->get("salutation");
    if (salutation !== "" && $salutation) {

    }

  }
}
