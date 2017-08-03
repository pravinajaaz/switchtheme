<?php

/**
 * @file
 * Contains \Drupal\switchtheme\Theme\SwitchthemeNegotiator.
 */

namespace Drupal\switchtheme\Theme;

use Drupal\Core\Theme\ThemeNegotiatorInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\switchtheme\Switchtheme;
use \Drupal\user\Entity\User;
/**
 * Switchtheme.
 */
class SwitchthemeNegotiator implements ThemeNegotiatorInterface {

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $route_match) {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function determineActiveTheme(RouteMatchInterface $route_match) {
    $account = \Drupal::currentUser();

    $set_theme = '';

    // The HTTP GET parameter 'theme' always has precedence.
    $theme = \Drupal::service('request_stack')->getCurrentRequest()->query->get('theme');
    $themes = Switchtheme::switchThemeOptions();

    if (!empty($theme)) {
      $set_theme = $theme;
    }
    // Check whether the user session contains a custom theme.
    if (isset($_SESSION['custom_theme'])) {
      $set_theme = $_SESSION['custom_theme'];
    }
    // Check whether the current user has a custom theme assigned.
    $user = User::load($account->id());
    if (!empty($user->theme)) {
      $set_theme = $user->theme;
    }

    if (isset($themes[$set_theme])) {
      return $set_theme;
    }

  }

}
