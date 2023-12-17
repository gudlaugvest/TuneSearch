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

    # $client_id = $config->get('client_id');
    # $client_secret = $config->get('client_secret');
    $client_id = 'ce8e6245f83a46e69d46ec60d668c1c3';
    $client_secret = '00d2ecb350454a8cbe844a4713799ce1';

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
      '#default_value' => $client_secret,
      '#description' => $this->t('Spotify API client secret.'),
      '#disabled' => true,
    ];

    return parent::buildForm($form, $form_state);
  }
}