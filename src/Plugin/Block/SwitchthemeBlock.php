<?php

/**
 * @file
 * Contains \Drupal\switchtheme\Plugin\Block\SwitchthemeBlock.
 */

namespace Drupal\switchtheme\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a Switchtheme form block.
 *
 * @Block(
 *   id = "switch_theme_form",
 *   admin_label = @Translation("Switch theme form"),
 * )
 */
class SwitchthemeBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $content = '';

    if (\Drupal::currentUser()->hasPermission('switch theme')) {
      $content = \Drupal::formBuilder()->getForm('Drupal\switchtheme\Form\SwitchthemeSwitchForm');
    }

    return array(
      '#children' => \Drupal::service('renderer')->render($content),
      '#cache' => array(
        'max-age' => 0,
      ),
    );
  }

}
