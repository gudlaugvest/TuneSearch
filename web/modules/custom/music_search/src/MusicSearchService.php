<?php

namespace Drupal\music_search;
use Drupal\spotify_api\SpotifyApiService;

class MusicSearchService {

    protected $spotifyApiService;

    public function __construct(SpotifyApiService $spotifyApiService) {
        $this->spotifyApiService = $spotifyApiService;
    }
}