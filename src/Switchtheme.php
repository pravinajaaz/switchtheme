<?php
/**
 * @file
 * Contains \Drupal\switchtheme\Switchtheme.
 */

namespace Drupal\switchtheme;

use Drupal\Core\Url;
/**
 * Helper functions.
 */
class Switchtheme {

  /**
   * Returns an #options list of enabled themes.
   */
  public static function switchThemeOptions() {
    $options = array();
    $theme_handler = \Drupal::service('theme_handler');
    $themes = $theme_handler->listInfo();
    foreach ($themes as $theme_name => $theme) {
      if (!empty($theme->info['hidden'])) {
        continue;
      }
      if (\Drupal::service('access_check.theme')->checkAccess($theme_name)) {
        $options[$theme_name] = $theme->info['name'];
      }
    }
    return $options;
  }

  /**
   * Returns switchtheme_options() with customized theme labels.
   */
  public static function switchThemeSelect() {
    $options = array();
    $config = \Drupal::config('switchtheme.settings');
    $settings = $config->get();
    foreach (Switchtheme::switchThemeOptions() as $name => $label) {
      $options[$name] = !empty($settings['switchtheme_' . $name]) ? $settings['switchtheme_' . $name] : $label;
    }
    asort($options);
    return $options;
  }

  /**
   * Renders a random theme with screenshot to switch to.
   */
  public static function switchThemeDisplayRandomBlock() {
    $theme_handler = \Drupal::service('theme_handler');
    $themes = $theme_handler->listInfo();
    shuffle($themes);
    foreach ($themes as $key => $theme) {
      if ($theme->status && !empty($theme->info['screenshot'])) {
        // Return the first theme with a screenshot.
        $image = array(
          '#theme' => 'image',
          '#uri' => $theme->info['screenshot'],
          '#alt' => t('Preview of @theme', array('@theme' => $theme->getName())),
        );
        $query = Url::fromRoute('<current>', [], ['absolute' => TRUE]);
        $build['theme'] = array(
          '#type' => 'link',
          '#title' => \Drupal::service('renderer')->render($image),
          '#url' => $query,
          '#options' => array(
            'query' => array(
              'theme' => $theme->getName(),
            ),
            'html' => TRUE,
          ),
        );
        return $build;
      }
    }
  }

}
