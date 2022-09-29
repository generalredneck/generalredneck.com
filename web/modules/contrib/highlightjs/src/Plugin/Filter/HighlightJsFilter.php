<?php

namespace Drupal\highlight_js\Plugin\Filter;

use Drupal\Core\Form\FormStateInterface;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;
use Drupal\Component\Utility\Html;

/**
 * Provide a filter to format code.
 *
 * Allows users to post code verbatim using &lt;code&gt; and &lt;?php ?&gt;
 * tags.
 *
 * @Filter(
 *   id = "highlight_js",
 *   title = @Translation("Highlight JS Filter"),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_TRANSFORM_REVERSIBLE,
 * )
 */
class HighlightJsFilter extends FilterBase {

  /**
   * {@inheritdoc}
   */
  public function prepare($text, $langcode) {
    $text = preg_replace_callback('@^<\?php(.+?)\?>@sm', [$this, 'escapeCodeTagCallback'], $text);
    $text = preg_replace_callback('@^<code>(.+?)</code>@sm', [$this, 'escapeCodeTagCallback'], $text);
    $text = preg_replace_callback('@^<code class="(.+?)">(.+?)</code>@sm', [$this, 'escapeCodeTagLangCallback'], $text);
    return $text;
  }

  /**
   * {@inheritdoc}
   */
  public function process($text, $langcode) {
    $text = preg_replace_callback('@\[highlightjs_code\](.+?)\[/highlightjs_code\]@s', [$this, 'processCodeCallback'], $text);
    $text = preg_replace_callback('@\[highlightjs_code class="(.+?)"\](.+?)\[/highlightjs_code\]@s', [$this, 'processCodeLangCallback'], $text);
    return new FilterProcessResult($text);
  }

  /**
   * {@inheritdoc}
   */
  public function tips($long = FALSE) {
    if ($long) {
      return $this->t('To post pieces of code, surround them with &lt;code&gt;...&lt;/code&gt; tags.');
    }
    else {
      return $this->t('You may post code using &lt;code&gt;...&lt;/code&gt; (generic) tags.');
    }
  }

  /**
   * Escape code blocks during input filter 'prepare'.
   *
   * @param string $text
   *   The string to escape.
   * @param string $type
   *   The type of code block, either 'code' or 'php'.
   * @param string $lang
   *   The code language type.
   *
   * @return string
   *   The escaped string.
   */
  protected function escape($text, $type = 'code', $lang = '') {
    // Note, pay attention to odd preg_replace-with-/e behaviour on slashes.
    $text = Html::escape(str_replace('\"', '"', $text));
    // Protect newlines from line break converter.
    $text = str_replace(["\r", "\n"], array('', '&#10;'), $text);
    $lang = empty($lang) ? '' : ' class="' . $lang . '"';
    // Add codefilter escape tags.
    $text = "[highlightjs_$type$lang]{$text}[/highlightjs_$type]";
    return $text;
  }

  /**
   * Callback to escape content of <code> elements.
   *
   * @param array $matches
   *   An array of the regex match result.
   *
   * @return string
   *   The escaped string.
   */
  protected function escapeCodeTagCallback($matches) {
    return $this->escape($matches[1], 'code');
  }

  /**
   * Callback to escape content of <code lang="php"> elements.
   *
   * @param array $matches
   *   An array of the regex match result.
   *
   * @return string
   *   The escaped string.
   */
  protected function escapeCodeTagLangCallback($matches) {
    return $this->escape($matches[2], 'code', $matches[1]);
  }

  /**
   * Callback to replace content of the <code> elements.
   *
   * @param $matches
   *   An array of the regex match result.
   *
   * @return string
   *   The escaped string.
   */
  protected function processCodeLangCallback($matches) {
    $lang = $matches[1];
    $text = $matches[2];
    return $this->processCode($text, $lang);
  }

  /**
   * Callback to replace content of the <code> elements.
   *
   * @param $matches
   *   An array of the regex match result.
   *
   * @return string
   *   The escaped string.
   */
  protected function processCodeCallback($matches) {
    return $this->processCode($matches[1]);
  }

  /**
   * Processes chunks of escaped code into HTML.
   *
   * @param string $text
   *   User text.
   * @param string $lang
   *   The code language.
   *
   * @return string
   *   The escaped string.
   */
  protected function processCode($text, $lang = '') {
    // Undo linebreak escaping.
    $text = str_replace('&#10;', "\n", $text);
    // Note, pay attention to odd preg_replace-with-/e behaviour on slashes
    $text = preg_replace("/^\n/", '', preg_replace('@</?(br|p)\s*/?>@', '', str_replace('\"', '"', $text)));
    // Trim leading and trailing line breaks.
    $text = trim($text, "\n");
    $class = empty($lang) ? '' : ' class="' . $lang . '"';
    $text = '<pre><code' . $class . '>' . $this->fixSpaces(str_replace(' ', '&nbsp;', $text)) . '</code></pre>';
    return $text;
  }

  /**
   * Change space in HTML entity to simple space.
   *
   * @param string $text
   *   User text.
   *
   * @return string
   *   The escaped string.
   */
  protected function fixSpaces($text) {
    return preg_replace('@&nbsp;(?!&nbsp;)@', ' ', $text);
  }

}
