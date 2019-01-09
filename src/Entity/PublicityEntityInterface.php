<?php

namespace Drupal\publicity\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining Publicity entity entities.
 */
interface PublicityEntityInterface extends ConfigEntityInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Returns a.
   *
   * @return $url_pu
   */
  public function getUrlPu();

  /**
   * Returns a.
   *
   * @return $urlPu
   */
  public function setUrlPu(string $UrlPu);

}
