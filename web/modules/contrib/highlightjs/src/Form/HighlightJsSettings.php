<?php

namespace Drupal\highlight_js\Form;

use Drupal\Core\Config\Config;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\highlight_js\HighlightJsStyleManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configuration form.
 */
class HighlightJsSettings extends ConfigFormBase {

  /**
   * Highlight JS configuration.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * Highlight JS style manager.
   *
   * @var \Drupal\highlight_js\HighlightJsStyleManager
   */
  protected $styleManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(Config $config, HighlightJsStyleManager $style_manager) {
    $this->config = $config;
    $this->styleManager = $style_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory')->getEditable('highlight_js.settings'),
      $container->get('highlight_js.style_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'highlight_js_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $url = Url::fromUri('http://highlightjs.org/static/test.html');
    $form['style'] = array(
      '#type' => 'select',
      '#title' => $this->t('Highlight JS style'),
      '#description' => $this->t('Select the default code style format. Please refer to the <a href=":url" target="_blank">Demo</a> page for a live demo of all the styles.', [':url' => $url->toString()]),
      '#options' => $this->styleManager->getStyleList(),
      '#default_value' => $this->config->get('style'),
      '#required' => TRUE,
    );

    $form['select_mode'] = array(
      '#type' => 'select',
      '#title' => $this->t('Selection mode'),
      '#options' => [
        'default' => $this->t('Highlight.js Default @code', ['@code' => '<pre><code>']),
        'code' => $this->t('Code @code', ['@code' => '<code>']),
      ],
      '#default_value' => $this->config->get('select_mode'),
      '#required' => TRUE,
    );

    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config
      ->set('style', $form_state->getValue('style'))
      ->set('select_mode', $form_state->getValue('select_mode'))
      ->save();

    return parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['highlight_js.settings'];
  }

}
