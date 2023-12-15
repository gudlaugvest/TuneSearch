<?php
// modules/custom/spotify_api/src/Form/SpotifyApiConfigurationForm.php

namespace Drupal\spotify_api\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class SpotifyApiConfigurationForm extends ConfigFormBase
{

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames()
  {
    return ['spotify_api.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'spotify_api_configuration_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $config = $this->config('spotify_api.settings');

    $client_id = $config->get('client_id');
    $client_secret = $config->get('client_secret');

    \Drupal::logger('spotify_api')->notice('Client ID: @client_id, Client Secret: @client_secret', [
      '@client_id' => $client_id,
      '@client_secret' => $client_secret,
    ]);

    $form['client_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Client ID'),
      '#default_value' => $client_id,
      '#description' => $this->t('Spotify API client ID.'),
      '#disabled' => true,
    ];

    $form['client_secret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Client Secret'),
      '#default_value' => 'hello world',
      '#description' => $this->t('Spotify API client secret.'),
      '#disabled' => true,
    ];

    return parent::buildForm($form, $form_state);
  }

}
