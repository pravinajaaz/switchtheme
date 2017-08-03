<?php

/**
 * @file
 * Contains \Drupal\switchtheme\EventSubscriber\InitSubscriber.
 */

namespace Drupal\switchtheme\EventSubscriber;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\switchtheme\Switchtheme;
use Drupal\Core\Form\FormState;
/**
 * InitSubscriber.
 */
class InitSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [ KernelEvents::REQUEST => ['onEvent', 0]];
  }

  /**
   * Switch theme.
   */
  public function onEvent() {
    // If there is a HTTP GET parameter 'theme', assign it as new theme.
    $theme = \Drupal::service('request_stack')->getCurrentRequest()->query->get('theme');
    if (isset($theme)) {
      // Manually validate the value.
      // @todo Consider switching the form's method to GET.
      $themes = Switchtheme::switchThemeOptions();
      if (isset($themes[$theme])) {
        $form_state = new FormState();
        $values['theme'] = $theme;
        $form_state->setValues($values);
        \Drupal::formBuilder()->submitForm('Drupal\switchtheme\Form\SwitchthemeSwitchForm', $form_state);
      }
    }
  }

}
