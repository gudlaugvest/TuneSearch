<?php

namespace Drupal\music_search\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\spotify_api\SpotifyApiService;

use Drupal;

class MusicSearchConfigurationForm extends ConfigFormBase {

  /**
   * The Spotify API service.
   *
   * @var \Drupal\spotify_api\SpotifyApiService
   */
  protected $spotifyApiService;

  /**
   * {@inheritdoc}
   */


  public static function create(ContainerInterface $container): ConfigFormBase {
    $instance = parent::create($container);
    $instance->spotifyApiService = $container->get('spotify_api.service');
    return $instance;
    ;
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['music_search.custom_music_search'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'music_search_configuration_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('music_search.custom_music_search');
    if ($form_state->has('cpage') && $form_state->get('cpage') == 2) {
      return $this->buildSpotifyPage($form, $form_state);
    }

    // Search by Artist
    $form['search_artist'] = [
      '#type' => 'textfield',
      '#title' => t('Search for an Artist'),
      '#description' => t('Please provide the name of an artist'),
      '#required' => TRUE,
    ];

    // Search by Album
    $form['search_album'] = [
      '#type' => 'textfield',
      '#title' => t('Search for an Album'),
      '#description' => t('Please provide the title of an album'),
      '#required' => TRUE,
    ];

    $form['next'] = [
      '#type' => 'submit',
      '#value' => 'Next',
      '#submit' => ['::submitForm', '::spotifyPage'],
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * Build the Spotify page.
   */
  public function buildSpotifyPage(array $form, FormStateInterface $form_state) {
    // ...

    // Get the artist info and album info from the form state
    $artistData = $form_state->get('artist_data');
    $albumData = $form_state->get('album_data');

    // Display the artist info
    $form['artist_info'] = [
      '#type' => 'fieldset',
      '#title' => t('Artist Info'),
    ];

    $form['artist_info']['artist_name'] = [
      '#type' => 'textfield',
      '#title' => t('Artist Name'),
      '#default_value' => isset($artistData['name']) ? $artistData['name'] : '',
      '#disabled' => TRUE,
    ];

    $form['artist_info']['artist_image'] = [
      '#type' => 'image',
      '#title' => t('Artist Image'),
      '#default_value' => isset($artistData['images'][0]['url']) ? $artistData['images'][0]['url'] : '',
      '#disabled' => TRUE,
    ];

    $form['artist_info']['artist_popularity'] = [
      '#type' => 'textfield',
      '#title' => t('Artist Popularity'),
      '#default_value' => isset($artistData['popularity']) ? $artistData['popularity'] : '',
      '#disabled' => TRUE,
    ];

    // Display the album info
    $form['album_info'] = [
      '#type' => 'fieldset',
      '#title' => t('Album Info'),
    ];

    $form['album_info']['album_name'] = [
      '#type' => 'textfield',
      '#title' => t('Album Name'),
      '#default_value' => isset($albumData['name']) ? $albumData['name'] : '',
      '#disabled' => TRUE,
    ];

    $form['album_info']['album_image'] = [
      '#type' => 'image',
      '#title' => t('Album Image'),
      '#default_value' => isset($albumData['images'][0]['url']) ? $albumData['images'][0]['url'] : '',
      '#disabled' => TRUE,
    ];

    $form['album_info']['album_popularity'] = [
      '#type' => 'textfield',
      '#title' => t('Album Popularity'),
      '#default_value' => isset($albumData['popularity']) ? $albumData['popularity'] : '',
      '#disabled' => TRUE,
    ];

    // Add checkboxes to select fields to save in the database
    $form['save_fields'] = [
      '#type' => 'checkboxes',
      '#title' => t('Select fields to save in the database'),
      '#options' => [
        'artist_name' => t('Artist Name'),
        'artist_image' => t('Artist Image'),
        'artist_popularity' => t('Artist Popularity'),
        'album_name' => t('Album Name'),
        'album_image' => t('Album Image'),
        'album_popularity' => t('Album Popularity'),
      ],
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => t('Save to Database'),
      '#submit' => ['::submitForm'],
    ];

    return $form;
  }


  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Save the data from the first page
    $searchData = $form_state->get('data');
    $selectedFields = $form_state->getValue('save_fields');

    // Example: Save selected fields to the database (adjust as needed)
    foreach ($selectedFields as $fieldName => $value) {
      if ($value) {
        $this->config('music_search.custom_music_search')
          ->set($fieldName, $searchData[$fieldName])
          ->save();
      }
    }

    // Redirect to the first page after saving data
    $form_state->setRedirect('music_search.spotify_page');
}

  /**
   * Submit handler for the "Next" button.
   */
  public function spotifyPage(array &$form, FormStateInterface $form_state) {
    // Get the search data from the first page
    $searchData = $form_state->getValues();
    
    // Set the data in the form state
    $form_state->set('data', $searchData);

    // Make a call to the Spotify API and retrieve data
    $accessToken = $this->spotifyApiService->getToken();
    $artistData = $this->spotifyApiService->getArtistInfo($searchData['search_artist'], $accessToken);
    $albumData = $this->spotifyApiService->getAlbumInfo($searchData['search_album'], $accessToken);

    // Set the retrieved data in the form state
    $form_state->set('artist_data', $artistData);
    $form_state->set('album_data', $albumData);

    // Move to the second page
    $form_state->set('cpage', 2);
    $form_state->setRebuild(true);
  }


}
