<?php

/**
 * @file
 * Contains \Drupal\switchtheme\Form\SwitchthemeSwitchForm.
 */

namespace Drupal\switchtheme\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use \Drupal\user\Entity\User;
use Drupal\switchtheme\Switchtheme;

/**
 * Administration settings form.
 */
class SwitchthemeSwitchForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'switchtheme_switch_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    global $theme_key;

    $options = Switchtheme::switchThemeSelect();
    // Nothing to switch if there is only one theme.
    if (count($options) < 2) {
      $form['#access'] = FALSE;
      return $form;
    }

    $form['widget'] = array(
      '#type' => 'container',
      '#attributes' => array('class' => array('container-inline')),
    );
    $form['widget']['theme'] = array(
      '#type' => 'select',
      '#title' => t('Change the way this site looks'),
      '#title_display' => 'attribute',
      '#options' => $options,
      '#required' => TRUE,
    );
    // Only if no custom theme could be determined, check whether we
    // can preselect the current theme.
    if (!isset($form['widget']['theme']['#default_value']) && isset($options[$theme_key])) {
      $form['widget']['theme']['#default_value'] = $theme_key;
    }

    $form['widget']['actions'] = array('#type' => 'actions');
    $form['widget']['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Switch'),
      '#id' => 'switchtheme-submit',
    );

    return $form;
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
    $user = \Drupal::currentUser();

    // Save the setting for authenticated users, if the "select different theme"
    // permission has been granted.
    if ($user->id() > 0 && \Drupal::currentUser()->hasPermission('select different theme')) {
      $account = User::load($user->id());
      $account->theme = $form_state->getValue('theme');
      $account->save();
    }

    // Otherwise save the setting in the user's session.
    elseif (\Drupal::currentUser()->hasPermission('switch theme')) {
      $_SESSION['custom_theme'] = $form_state->getValue('theme');
    }
  }

}
