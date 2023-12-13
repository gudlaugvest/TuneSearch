<?php

namespace Drupal\spotify_api\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Controller for the Spotify API site.
 */
class SpotifyApiController extends ControllerBase {

  /**
   * This is a Spotify API controller
   * @return array
   * Spotify API
   */
  public function spotifyAPI() : array {
    return [
      '#markup' => $this->t('Spotify API')
    ];
  }
}
