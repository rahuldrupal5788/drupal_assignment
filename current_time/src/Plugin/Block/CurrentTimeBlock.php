<?php
/**
 * @file
 * Contains \Drupal\current_time\Plugin\Block\CurrentTimeBlock.
 */

namespace Drupal\current_time\Plugin\Block;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormInterface;
use Drupal\current_time\Services\CustomService;

/**
 * Provides a 'Current Time' block.
 *
 * @Block(
 *   id = "current_time_block",
 *   admin_label = @Translation("Current Time"),
 * )
 */
class CurrentTimeBlock extends BlockBase implements ContainerFactoryPluginInterface {
  /**
   * @var \Drupal\current_time\Services\CustomService 
   */
  protected $customService;

  /**
   * Class constructor.
   */  
  public function __construct(array $configuration, $plugin_id, $plugin_definition, CustomService $custom_service)  {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->customService = $custom_service;
  }

  /**
   * {@inheritdoc}
   * 
   * static create function provided by the ContainerFactoryPluginInterface.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_time.custom_service')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Get the configuration object
    $config = $this->customService->fetchConfigFormValues();
    // Get the value of the keys 
    $country = $config->get('country');
    $city = $config->get('city');
    $timezone = $config->get('timezone');
    // Get datetime according to timezone
    $datetime = $this->customService->fetchDateTimeFromTimezone($timezone); 
    return [
      '#theme' => 'currentdatetime',
      '#country' => $country,
      '#city' => $city,
      '#datetime' => $datetime,
      '#cache' => [
        'tags' => ['current_time_config_form_tag'],
      ],
    ];  
  }
}