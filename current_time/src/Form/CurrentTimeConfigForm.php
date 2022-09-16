<?php

namespace Drupal\current_time\Form;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Cache\Cache;

/**
 * Class CurrentTimeConfigForm.
 */
class CurrentTimeConfigForm extends ConfigFormBase {
  /**
   * MessengerInterface.   
   */
  protected $messenger;

  /**
   * {@inheritdoc}
   */  
  public function __construct(MessengerInterface $messenger)  {
    $this->messenger = $messenger;
  }


  /**
   * {@inheritdoc}
   */
   // Load the service required to construct this class.
   public static function create(ContainerInterface $container) {
    return new static(
      $container->get('messenger')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'current_time.config_form',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'current_time_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('current_time.config_form');
    $form['country'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Country'),
      '#required' => TRUE,
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('country'),
      '#description' => $this->t('Enter the country name.'),
    ];
    $form['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#required' => TRUE,
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('city'),
      '#description' => $this->t('Enter the city name.'),
    ];

    $timezones = [
      'America/Chicago' => 'America/Chicago',
      'America/New_York' => 'America/New_York',
      'Asia/Tokyo' => 'Asia/Tokyo',
      'Asia/Dubai' => 'Asia/Dubai',
      'Asia/Kolkata' => 'Asia/Kolkata',
      'Europe/Amsterdam' => 'Europe/Amsterdam',
      'Europe/Oslo' => 'Europe/Oslo',
      'Europe/London' => 'Europe/London'
    ];
    $form['timezone'] = [
      '#type' => 'select',
      '#title' => $this->t('Time zone'),
      '#required' => TRUE,
      '#default_value' => $config->get('timezone'),
      '#options' => $timezones,
      '#description' => $this->t('Select the desired time zone.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('current_time.config_form');    
    $config->set('country', $form_state->getValue('country'));
    $config->set('city', $form_state->getValue('city'));
    $config->set('timezone', $form_state->getValue('timezone'));
    $config->save();     
    // Invalidate Cache
    Cache::invalidateTags(array('current_time_config_form_tag'));

    $this->messenger->addMessage(t('Form has been submitted successfully.'));
    return parent::submitForm($form, $form_state);
  }
}
