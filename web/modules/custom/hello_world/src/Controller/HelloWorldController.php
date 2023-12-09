<?php
// don't have to state src
namespace Drupal\hello_world\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\hello_world\HelloWorldSalutation;

class HelloWorldController extends ControllerBase {
  protected $salutation;

  public function __construct(HelloWorldSalutation $salutation) {
    $this->salutation = $salutation;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get(id: "hello_world_salutation")
    );
  }
  public function helloWorld() {
    return [
      "#markup" => $this-> salutation->getSalutation(),
    ];
  }
}
