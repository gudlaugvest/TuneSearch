<?php

namespace Drupal\spotify_api;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;
use Symfony\Component\DependencyInjection\Reference;

class SpotifyApiService {

  private $tokenEndpoint = 'https://accounts.spotify.com/api/token';
  private $config;
  private $clientId = 'ce8e6245f83a46e69d46ec60d668c1c3';
  private $clientSecret = '00d2ecb350454a8cbe844a4713799ce1';

  public function __construct(ConfigFactoryInterface $configFactory) {
    $this->config = $configFactory->get('spotify_api.settings');
  }

  public function getToken() {
    $clientId = $this->clientId;
    $clientSecret = $this->clientSecret;

    $ch = curl_init($this->tokenEndpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
      'grant_type' => 'client_credentials',
      'client_id' => $clientId,
      'client_secret' => $clientSecret,
    ]);

    $response = curl_exec($ch);

    curl_close($ch);

    $data = json_decode($response, true);

    if (isset($data['access_token'])) {
      return $data['access_token'];
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
}
