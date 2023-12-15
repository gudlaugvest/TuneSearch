<?php

namespace Drupal\spotify_api;

use Drupal\Core\Config\ConfigFactoryInterface;
use Psr\Log\LoggerInterface;

class SpotifyApiService {

  private $tokenEndpoint = 'https://accounts.spotify.com/api/token';
  private $config;
  private $logger;

  public function __construct(ConfigFactoryInterface $configFactory, LoggerInterface $logger) {
    $this->config = $configFactory->get('spotify_api.settings');
    $this->logger = $logger;
  }

  public function getToken() {
    // Retrieve client ID and client secret from Drupal configuration.
    $clientId = $this->config->get('client_id');
    $clientSecret = $this->config->get('client_secret');
    \Drupal::logger('spotify_api')->notice('@client_id');



      // Prepare the cURL request.
    $ch = curl_init($this->tokenEndpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
      'grant_type' => 'client_credentials',
      'client_id' => $clientId,
      'client_secret' => $clientSecret,
    ]);

    // Execute the request.
    $response = curl_exec($ch);

    // Close the cURL session.
    curl_close($ch);

    // Decode the JSON response.
    $data = json_decode($response, true);

    // Check if the access token is present in the response.
    if (isset($data['access_token'])) {
      $this->logger->info('Access token obtained successfully.');
      return $data['access_token'];
    } else {
      // Log error.
      $this->logger->error('Error obtaining access token.');
      return null;
    }
  }

  public function getArtistInfo($artistId, $accessToken) {
    // Example endpoint to get artist information
    $artistEndpoint = "https://api.spotify.com/v1/artists/{$artistId}";

    // Prepare the cURL request with the access token in headers.
    $ch = curl_init($artistEndpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Authorization: Bearer ' . $accessToken,
    ]);

    // Execute the request.
    $response = curl_exec($ch);

    // Close the cURL session.
    curl_close($ch);

    // Decode the JSON response.
    $artistData = json_decode($response, true);

    // Handle the artist data as needed.

    return $artistData;
  }

  public function getAlbumInfo($albumId, $accessToken) {
    // Example endpoint to get album information
    $albumEndpoint = "https://api.spotify.com/v1/albums/{$albumId}";

    // Prepare the cURL request with the access token in headers.
    $ch = curl_init($albumEndpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Authorization: Bearer ' . $accessToken,
    ]);

    // Execute the request.
    $response = curl_exec($ch);

    // Close the cURL session.
    curl_close($ch);

    // Decode the JSON response.
    $albumData = json_decode($response, true);

    // Handle the album data as needed.

    return $albumData;
  }
}
