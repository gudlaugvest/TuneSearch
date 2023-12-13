<?php
namespace Drupal\spotify_api\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form for configuring Spotify API settings.
 */
class SpotifyApiConfigurationForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['spotify_api.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'spotify_api_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('spotify_api.custom_spotify_api');

    // Spotify Client ID
    $form['spotify_client_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Spotify Client ID'),
      '#default_value' => $config->get('spotify_client_id'),
    ];

    // Spotify Client Secret
    $form['spotify_client_secret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Spotify Client Secret'),
      '#default_value' => $config->get('spotify_client_secret'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('spotify_api.settings')
      ->set('spotify_client_id', $form_state->getValue('spotify_client_id'))
      ->set('spotify_client_secret', $form_state->getValue('spotify_client_secret'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}

