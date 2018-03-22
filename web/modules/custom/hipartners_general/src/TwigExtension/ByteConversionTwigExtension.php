<?php

namespace Drupal\hipartners_general\TwigExtension;


class ByteConversionTwigExtension extends \Twig_Extension {

  /**
   * Gets filters
   *
   * @return array
   */
  public function getFilters() {
    return array(
      new \Twig_SimpleFilter('format_bytes', array($this, 'formatBytes')),
    );
  }

  public function getName() {
    return 'format_bytes';
  }

  /**
   *
   * @param $bytes
   * @param int $precision
   * @return string
   */
  function formatBytes($bytes, $precision = 0) {
    $bytes = floatval($bytes);
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);

    return round($bytes, $precision) . ' ' . $units[$pow];
  }

}