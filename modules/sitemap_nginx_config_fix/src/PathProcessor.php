<?php

namespace Drupal\sitemap_nginx_config_fix;

use Drupal\Core\PathProcessor\InboundPathProcessorInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Path processor  for fixing sitemap.xml infinite redirect loop.
 */
class PathProcessor implements InboundPathProcessorInterface {

  /**
   * {@inheritdoc}
   */
  public function processInbound($path, Request $request) {
    $check_path = $path;
    if (strpos($check_path, '?')) {
      list($check_path) = explode('?', $check_path);
    }

    if ($check_path == '/sitemap.xml') {
      \Drupal::request()->attributes->set('_disable_route_normalizer', TRUE);
    }

    return $path;
  }
}
