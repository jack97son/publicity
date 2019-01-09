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
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/publicity_entity/{publicity_entity}",
 *     "add-form" = "/admin/structure/publicity_entity/add",
 *     "edit-form" = "/admin/structure/publicity_entity/{publicity_entity}/edit",
 *     "delete-form" = "/admin/structure/publicity_entity/{publicity_entity}/delete",
 *     "collection" = "/admin/structure/publicity_entity"
 *   }
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
  protected $label;

  /**
   * The Publicity entity url.
   *
   * @var string
   */
  protected $UrlPu;


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
