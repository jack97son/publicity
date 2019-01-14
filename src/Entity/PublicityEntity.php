<?php

namespace Drupal\publicity\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the Publicity entity entity.
 *
 * @ConfigEntityType(
 *   id = "publicity_entity",
 *   label = @Translation("Publicity entity"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\publicity\PublicityEntityListBuilder",
 *     "form" = {
 *       "add" = "Drupal\publicity\Form\PublicityEntityForm",
 *       "edit" = "Drupal\publicity\Form\PublicityEntityForm",
 *       "delete" = "Drupal\publicity\Form\PublicityEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\publicity\PublicityEntityHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "publicity_entity",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *     "label" = "name"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/publicity_entity/{publicity_entity}",
 *     "add-form" = "/admin/structure/publicity_entity/add",
 *     "edit-form" = "/admin/structure/publicity_entity/{publicity_entity}/edit",
 *     "delete-form" = "/admin/structure/publicity_entity/{publicity_entity}/delete",
 *     "collection" = "/admin/structure/publicity_entity"
 *   },
 *
 * )
 */
class PublicityEntity extends ConfigEntityBase implements PublicityEntityInterface {

  /**
   * The Publicity entity ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Publicity entity label.
   *
   * @var string
   */
  public $label;

  /**
   * The Publicity entity url.
   *
   * @var string
   */
  protected $UrlPu;

  /**
   * The Advertising entity breakpoints.
   *
   * @var array
   */
  public $breakpoints;
   /**
   * Set the default place to put an AD.
   *
   * @param string $place
   *   The place to set.
   *
   * @return string
   */
  public function setPlace($place) {
    return $this->set('place', $place);
  }
  /**
   * Get the default place to put an AD.
   *
   * @return string
   */
  public function getPlace() {
    return $this->get('place');
  }
  /**
   * Set the default breakpoints.
   *
   * @param string $breakpoints
   *   The breakpoints to set.
   *
   * @return string
   */
  public function setBreakpoints($breakpoints) {
    $serializer = \Drupal::service('serialization.phpserialize');
    $this->breakpoints = $serializer->encode($breakpoints);
  }
  /**
   * Get the breakpoints.
   *
   * @return string
   */
  public function getBreakpoints() {
    $serializer = \Drupal::service('serialization.phpserialize');
    return $serializer->decode($this->breakpoints);
  }



  /**
   * Get the Publicity entity url.
   *
   * @return  string
   */ 
  public function getUrlPu()
  {
    return $this->UrlPu;
  }

  /**
   * Set the Publicity entity url.
   *
   * @param  string  $urlPu  The Publicity entity url.
   *
   * @return  self
   */ 
  public function setUrlPu(string $UrlPu)
  {
    $this->UrlPu = $UrlPu;

    return $this;
  }
}
