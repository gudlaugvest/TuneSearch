<?php

namespace Drupal\music_search\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\spotify_api\SpotifyApiService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Music search form.
 */
class MusicSearchConfigurationForm extends ConfigFormBase {


  /**
   * The Spotify API service.
   *
   * @var \Drupal\spotify_api\SpotifyApiService
   */
  protected $spotifyApiService;

  /**
   * MusicSearchConfigurationForm constructor.
   *
   * @param \Drupal\spotify_api\SpotifyApiService $spotifyApiService
   *   The Spotify API service.
   */
  public function __construct(SpotifyApiService $spotifyApiService) {
    $this->spotifyApiService = $spotifyApiService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('spotify_api.service')
    );
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
    ];

    // Search by Album
    $form['search_album'] = [
      '#type' => 'textfield',
      '#title' => t('Search for an Album'),
      '#description' => t('Please provide the title of an album'),
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
    
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Save the data from the first page
    $form_state->set('data', [
      'search_artist' => $form_state->getValue('search_artist'),
      'search_album' => $form_state->getValue('search_album'),
    ]);

    // Redirect to the first page after saving data
    $form_state->setRedirect('music_search.spotify_page');
  }

  /**
   * Submit handler for the "Next" button.
   */
  public function spotifyPage(array &$form, FormStateInterface $form_state) {
    // Get the search data from the first page
    $searchData = $form_state->get('data');

    // Move to the second page
    $form_state->set('cpage', 2);
    $form_state->setRebuild(true);
  }
}
