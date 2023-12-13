<?php

/**
 * @file
 * SpotifyApiService class.
 */

namespace Drupal\spotify;

class SpotifyApiService {

  const SPOTIFY_TOKEN_URL = 'https://accounts.spotify.com/api/token';

  protected $clientId;
  protected $clientSecret;
  protected $accessToken;

  /**
   * SpotifyApiService constructor.
   */
  public function __construct() {
    $this->clientId = \Drupal::config('spotify.settings')->get('client_id');
    $this->clientSecret = \Drupal::config('spotify.settings')->get('client_secret');
  }

  /**
   * Set the access token.
   *
   * @param string $token
   *   The Spotify access token.
   */
  public function setToken($token) {
    $this->accessToken = $token;
  }

  /**
   * Get the access token.
   *
   * @return string|null
   *   The Spotify access token or null if not set.
   */
  public function getToken() {
    return $this->accessToken;
  }

  /**
   * Reset the access token.
   */
  public function resetToken() {
    $this->accessToken = '';
  }

  /**
   * Perform Spotify API authorization with code.
   *
   * @return string
   *   The access token obtained.
   */
  public function accessWithCodeAuthorization() {
    $postData = [
      'grant_type' => 'client_credentials',
      'client_id' => $this->clientId,
      'client_secret' => $this->clientSecret,
    ];

    $headers = [
      'Content-Type: application/x-www-form-urlencoded',
    ];

    $ch = curl_init(self::SPOTIFY_TOKEN_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    if (isset($data['access_token'])) {
      $token = $data['access_token'];
      $this->setToken($token);
      return $token;
    }

    // Handle error or return null.
    return null;
  }

  /**
   * Get cURL headers for Spotify API requests with access token.
   *
   * @return array
   *   An array of headers.
   */
  public function spotifyApiToken() {
    if ($this->getToken() != null) {
      $token = $this->getToken();
    } else {
      $token = $this->accessWithCodeAuthorization();
    }

    $headers = [
      'headers' => [
        'Accept' => 'application/json',
        'Content-Type'=> 'application/json',
        'Authorization' => 'Bearer ' . $token,
      ],
    ];

    return $headers;
  }
}
