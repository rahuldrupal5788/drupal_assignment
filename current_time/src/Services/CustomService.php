<?php

namespace Drupal\current_time\Services;

use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * CustomService Class
 */
class CustomService {
  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Class constructor.
   */  
  public function __construct(ConfigFactoryInterface $config_factory)  {
    $this->configFactory = $config_factory;
  }

  /**
   * Fetch config form values.
   */
  public function fetchConfigFormValues() {
    $configObject = $this->configFactory->get('current_time.config_form');  
    return $configObject;
  }

  /**
   * Fetch current datetime from timezone.
   */
  public function fetchDateTimeFromTimezone($timezone) {
    $date = new \DateTime("now", new \DateTimeZone($timezone) );
      $day = $date->format('d');
    if($day == 1) {
      $st = 'st';
    } elseif($day == 2) {
      $st = 'nd';
    } elseif($day == 3) {
      $st = 'rd';
    } else {
      $st = 'th';
    }
    $monthYear = $date->format('M Y');
    $time =  $date->format('h:i A');
    $datetime = $day.''.$st.' '.$monthYear.' - '.$time;
    return $datetime;
  }
}