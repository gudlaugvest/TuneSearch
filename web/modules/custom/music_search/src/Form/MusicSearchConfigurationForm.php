<?php

namespace Drupal\music_search\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Music search form.
 */
class MusicSearchConfigurationForm extends ConfigFormBase {

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

    // Search by Song
    $form['search_song'] = [
      '#type' => 'textfield',
      '#title' => t('Search for a Song'),
      '#description' => t('Please provide the title of a song'),
      '#default_value' => $config->get('search_song'),
    ];

    // Search by Artist
    $form['search_artist'] = [
      '#type' => 'textfield',
      '#title' => t('Search for an Artist'),
      '#description' => t('Please provide the name of an artist'),
      '#default_value' => $config->get('search_artist'),
    ];

    // Search by Album
    $form['search_album'] = [
      '#type' => 'textfield',
      '#title' => t('Search for an Album'),
      '#description' => t('Please provide the title of an album'),
      '#default_value' => $config->get('search_album'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('music_search.custom_music_search')
      ->set('search_song', $form_state->getValue('search_song'))
      ->set('search_artist', $form_state->getValue('search_artist'))
      ->set('search_album', $form_state->getValue('search_album'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}
