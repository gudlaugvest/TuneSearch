<?php

namespace Drupal\spotify_api;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;
use Symfony\Component\DependencyInjection\Reference;
use Drupal\Core\Config\ConfigFactoryInterface;

class SpotifyApiService {

  private $tokenEndpoint = 'https://accounts.spotify.com/api/token';
  private $config;
  protected $clientId = 'ce8e6245f83a46e69d46ec60d668c1c3';
  protected $clientSecret = '00d2ecb350454a8cbe844a4713799ce1';


  public function getToken() {
    $auth_str = $this->clientId . ':' . $this->clientSecret;
    $auth_base64 = base64_encode($auth_str);

    $ch = curl_init($this->tokenEndpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'grant_type' => 'client_credentials',
    ]));

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded',
        'Authorization: Basic ' . $auth_base64,
    ]);

    $response = curl_exec($ch);

    if ($response === false) {
        \Drupal::logger('spotify_api')->error('CURL Error: ' . curl_error($ch));
    }

    \Drupal::logger('spotify_api')->notice('Token API Request Payload: ' . print_r([
        'grant_type' => 'client_credentials',
        'client_id' => $this->clientId,
        'client_secret' => $this->clientSecret,
    ], true));

    \Drupal::logger('spotify_api')->notice('Token API Response: ' . $response);

    curl_close($ch);

    $data = json_decode($response, true);

    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        \Drupal::logger('spotify_api')->error('Error decoding JSON: ' . json_last_error_msg());
        return null;
    }

    if (isset($data['error'])) {
        \Drupal::logger('spotify_api')->error('Token API Error: ' . $data['error']);
        return null;
    }

    $accessToken = $data['access_token'];

    if (isset($accessToken)) {
        \Drupal::logger('spotify_api')->notice('Access Token: ' . $accessToken);
    } else {
        return null;
    }

    if (isset($accessToken)) {
        return $accessToken;
    } else {
        return null;
    }
}

  public function getArtistInfo($artistId, $accessToken) {
    $artistEndpoint = "https://api.spotify.com/v1/artists/{$artistId}";

    $ch = curl_init($artistEndpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Authorization: Bearer ' . $accessToken,
    ]);

    $response = curl_exec($ch);

    curl_close($ch);

    $artistData = json_decode($response, true);

    return $artistData;
  }

  public function getAlbumInfo($albumId, $accessToken) {
    $albumEndpoint = "https://api.spotify.com/v1/albums/{$albumId}";

    $ch = curl_init($albumEndpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Authorization: Bearer ' . $accessToken,
    ]);

    $response = curl_exec($ch);

    curl_close($ch);

    $albumData = json_decode($response, true);

    return $albumData;
  }

  /**
   * Get the artist ID based on the artist name.
   *
   * @param string $artistName
   *   The name of the artist.
   * @param string $accessToken
   *   The Spotify API access token.
   *
   * @return string|null
   *   The artist ID or null if not found.
   */
  public function getArtistIdByName($artistName, $accessToken) {
    // Search for the artist using the Spotify API search endpoint
    $searchEndpoint = 'https://api.spotify.com/v1/search';
    $query = http_build_query([
        'q' => $artistName,
        'type' => 'artist',
    ]);

    $url = $searchEndpoint . '?' . $query;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Authorization: Bearer ' . $accessToken,
    ]);

    $response = curl_exec($ch);

    curl_close($ch);

    $data = json_decode($response, true);

    // Extract the artist ID from the search results
    if (isset($data['artists']['items'][0]['id'])) {
      return $data['artists']['items'][0]['id'];
    } else {
      return null;
    }
  }
}
