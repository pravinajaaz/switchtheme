<?php

/**
 * @file
 * Contains \Drupal\switchtheme\Form\SwitchthemeAdminSettings.
 */

namespace Drupal\switchtheme\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;
use Drupal\switchtheme\Switchtheme;

/**
 * Administration settings form.
 */
class SwitchthemeAdminSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'switchtheme_admin';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['switchtheme.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('switchtheme.settings');
    $settings = $config->get();

    $options = Switchtheme::switchThemeOptions();
    foreach ($options as $name => $label) {
      $form['switchtheme_' . $name] = array(
        '#type' => 'textfield',
        '#title' => $label,
        '#default_value' => !empty($settings['switchtheme_' . $name]) ? $settings['switchtheme_' . $name] : '',
      );
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('switchtheme.settings');

    foreach (Element::children($form) as $variable) {
      $config->set($variable, $form_state->getValue($form[$variable]['#parents']));
    }
    $config->save();

    if (method_exists($this, '_submitForm')) {
      $this->_submitForm($form, $form_state);
    }

    parent::submitForm($form, $form_state);
  }

}
