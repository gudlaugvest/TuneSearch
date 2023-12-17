<?php

// Declare the namespace.
namespace Drupal\music_search\Controller;


// Import the MusicSearchService class.
use Drupal\Core\Controller\ControllerBase;
use Drupal\music_search\MusicSearchService;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MusicSearchController extends ControllerBase {
    /**
     * Music Search Controller
     * @return array
     * Music Search API
     */

    public function musicSearch() {
        return [
            '#markup' => $this->t('Music Search API'),
        ]
    }
}