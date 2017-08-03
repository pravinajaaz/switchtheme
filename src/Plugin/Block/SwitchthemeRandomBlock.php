<?php

/**
 * @file
 * Contains \Drupal\switchtheme\Plugin\Block\SwitchthemeRandomBlock.
 */

namespace Drupal\switchtheme\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\switchtheme\Switchtheme;

/**
 * Provides a Switchtheme Random block.
 *
 * @Block(
 *   id = "switch_random",
 *   admin_label = @Translation("Random theme"),
 * )
 */
class SwitchthemeRandomBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $content = '';

    if (\Drupal::currentUser()->hasPermission('switch theme')) {
      $content = Switchtheme::switchThemeDisplayRandomBlock();
    }

    return array(
      '#children' => \Drupal::service('renderer')->render($content),
      '#cache' => array(
        'max-age' => 0,
      ),
    );
  }

}
