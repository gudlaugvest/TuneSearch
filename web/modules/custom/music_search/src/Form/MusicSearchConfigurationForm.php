<?php

namespace Drupal\music_search\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\spotify_api\SpotifyApiService;

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
    $page = $form_state->get('cpage') ?? 1;

    if ($page === 1) {
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
        '#submit' => ['::submitForm'],
      ];
      
    } elseif ($page === 2) {
      // Build the Spotify page
      $form = $this->buildSpotifyPage($form, $form_state);
    }

    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $page = $form_state->get('cpage') ?? 1;

    if ($page === 1) {
      // Get the search data from the first page
      $searchData = $form_state->getValues();
  
      // Make a call to the Spotify API and retrieve data
      $accessToken = $this->spotifyApiService->getToken();
      
      // Get the artist ID from the provided artist name
      $artistName = $searchData['search_artist'];
      $artistId = $this->spotifyApiService->getArtistIdByName($artistName, $accessToken);

      if ($artistId) {
        // Artist ID found, you can use it as needed
        \Drupal::logger('spotify_api')->notice('Artist ID: ' . $artistId);
      } else {
        // Handle the case where the artist ID couldn't be found
        \Drupal::logger('spotify_api')->error('Unable to retrieve Artist ID for: ' . $artistName);
        // You might want to set a form error or take other appropriate actions
      }

      // Rest of your existing code...
    } elseif ($page === 2) {
      // Rest of your existing code...
    }

    // Redirect to the next page or stay on the same page
    $form_state->set('cpage', $page + 1);
    $form_state->setRebuild(true);
  }

  /**
   * Build the Spotify page.
   */
  public function buildSpotifyPage(array $form, FormStateInterface $form_state) {
    // Get the artist info, album info, and search data from the form state
    $artistData = $form_state->get('artist_data');
    $albumData = $form_state->get('album_data');
    $searchData = $form_state->get('search_data');

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
        'album_name' => t('Album Name'),
        'album_image' => t('Album Image'),
        'album_popularity' => t('Album Popularity'),
      ],
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => t('Save to Database'),
    ];

    return $form;
  }
}
