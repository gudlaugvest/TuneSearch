<?php

namespace Drupal\music_search\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\music_search\MusicSearchServices;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller for the Music Search site.
 */

class MusicSearchController extends ControllerBase {
  /**
   * This is the Music Search Controller
   * @return array
   *  Music Search API
   */
  public function musicSearch() : array {
    return [
      '#markup' => $this->t('Search your music here'),
    ];
  }
}
