<?php

namespace Drupal\redirect_by_ip\Services;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;

/**
 * Class FreeGeoIpLocatorService
 * @package Drupal\redirect_by_ip\Services
 */
class FreeGeoIpLocatorService {
  const URL = 'https://freegeoip.net/json/';

  /**
   * Cache Manager.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected $cacheManager;

  /**
   * Drupal Logger.
   *
   * @var \Drupal\redirect_by_ip\Services\LoggerChannelFactory
   */
  protected $logger;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    CacheBackendInterface $cacheManager,
    LoggerChannelFactoryInterface $logger
  ) {
    $this->logger = $logger;
    $this->cacheManager = $cacheManager;
  }

  /**
   * Returns the country code of the user ip address.
   *
   * @param string $ip
   *   The ip address of the user.
   *
   * @return string
   *   The country code of the user ip address.
   */
  public function locate($ip) {
    $cacheId = 'ip' . $ip;

    if ($cache = $this->cacheManager->get($cacheId)) {
      return $cache->data;
    }

    $url = self::URL . $ip;
    $content = $this->getCall($url);

    if ($content !== NULL) {

      $geoJson = json_decode($content, TRUE);

      if (!empty($geoJson['country_code'])) {

        $this->cacheManager->set($cacheId, $geoJson['country_code']);

        return $geoJson['country_code'];

      }
    }

    return NULL;
  }

  /**
   * Make a call to an url with the curl library.
   *
   * @param string $url
   *   The you want to call.
   *
   * @return mixed|null
   *   Result of the call.
   */
  public function getCall($url) {
    $defaults = array(
      CURLOPT_URL => $url,
      CURLOPT_HEADER => 0,
      CURLOPT_RETURNTRANSFER => TRUE,
      CURLOPT_TIMEOUT => 4,
    );

    $ch = curl_init();
    curl_setopt_array($ch, $defaults);

    if (!$result = curl_exec($ch)) {
      $error = curl_error($ch);
      // @todo log errors.
      $this->logger->get('curl_error')->error($error);
      $result = NULL;
    }
    curl_close($ch);
    return $result;
  }

}
