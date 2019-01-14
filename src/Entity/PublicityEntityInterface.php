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

  /**
   * Get the default place to put an AD.
   *
   * @return string
   */
  public function getPlace();
  /**
   * Set the default place to put an AD.
   *
   * @param string $place
   *   The place to set.
   *
   * @return string
   */
  public function setPlace($place);
  /**
   * Get the breakpoints.
   *
   * @return string
   */
  public function getBreakpoints();
  
  /**
   * Set the default breakpoints.
   *
   * @param string $breakpoints
   *   The breakpoints to set.
   *
   * @return string
   */
  public function setBreakpoints($breakpoints);
  
}
