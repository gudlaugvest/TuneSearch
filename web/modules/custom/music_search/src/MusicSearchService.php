<?php

namespace Drupal\music_search;
use Drupal\spotify_api\SpotifyApiService;
use Drupal\Core\Config\ConfigFactoryInterface;

class MusicSearchService {

    protected $spotifyApiService;
    protected $config_factory;

    public function __construct(ConfigFactoryInterface $config_factory, SpotifyApiService $spotifyApiService) {
        $this->config_factory = $config_factory;
        $this->spotifyApiService = $spotifyApiService;
    }
}
