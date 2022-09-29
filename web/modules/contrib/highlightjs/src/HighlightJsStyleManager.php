<?php

namespace Drupal\highlight_js;

use Drupal\Component\Utility\Unicode;
use Drupal\Core\File\FileSystemInterface;

/**
 * Highlight.js style manager.
 */
class HighlightJsStyleManager {

  /**
   * File system service.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

  /**
   * Constructs a Highlightjs Style Manager.
   *
   * @param \Drupal\Core\File\FileSystemInterface $file_system
   *   File system service.
   */
  public function __construct(
    FileSystemInterface $file_system
  ) {
    $this->fileSystem = $file_system;
  }

  /**
   * List the available themes.
   */
  public function getStyleList() {
    $directory = 'libraries/highlightjs/styles/';
    $list = [];
    if (!empty($directory)) {
      $files = $this->fileSystem->scanDirectory($directory, '/.*\.css$/', ['key' => 'name']);
      foreach ($files as $key => $fileinfo) {
        $list[mb_strtolower($key)] = Unicode::ucfirst($key);
      }
      natcasesort($list);
    }

    return $list;
  }

}
