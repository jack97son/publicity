<?php

namespace Drupal\publicity\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class PublicityEntityForm.
 */
class PublicityEntityForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $publicity_entity = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#maxlength' => 255,
      '#default_value' => $publicity_entity->label(),
      '#description' => $this->t("Name for the Publicity."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $publicity_entity->id(),
      '#machine_name' => [
        'exists' => '\Drupal\publicity\Entity\PublicityEntity::load',
      ],
      '#disabled' => !$publicity_entity->isNew(),
    ];
    
    /* You will need additional form elements for your custom properties. */

    $form['url_pu'] = [
      '#type' => 'url',
      '#default_value' => $publicity_entity->get('url_pu'),
      '#title' => $this->t('Url publicity'),
      '#description' => $this->t("Url the publicity"),
    ];

    $form['render'] = [
      '#type' => 'select',
      '#default_value' =>  $publicity_entity->get('render'),
      '#title' => $this->t('Render'),
      '#description' => $this->t("Select your page"),
      '#options' => [
        '1' => 'Home page',
        'Taxonomies' => $this->taxonomy_vocabulary_get_names(),
        'Content types' => $this->content_type_get_names(),
      ],

    ];

    $form['breakpoints_desktop'] = [
      '#type' => 'details',
      '#title' => t('Desktop'), 
    ];

    $form['breakpoints_mobile'] = [
      '#type' => 'details',
      '#title' => t('Mobile'), 
    ];

    $form['breakpoints_tablet'] = [
      '#type' => 'details',
      '#title' => t('Tablet'), 
    ];

    $form['breakpoints_desktop']['height'] = [
      '#type' => 'number',
      '#title' => 'height',
      '#min' => 1,
    ];

    $form['breakpoints_mobile']['height'] = [
      '#type' => 'number',
      '#title' => 'height',
      '#min' => 1,
    ];

    $form['breakpoints_tablet']['height'] = [
      '#type' => 'number',
      '#title' => 'height',
      '#min' => 1,
    ];

    $form['breakpoints_desktop']['width'] = [
      '#type' => 'number',
      '#title' => 'width',
      '#min' => 1,
      '#default_value' => $publicity_entity->get('width'),
    ];

    $form['breakpoints_mobile']['width'] = [
      '#type' => 'number',
      '#title' => 'width',
      '#min' => 1,
      '#default_value' => $publicity_entity->get('width'),
    ];

    $form['breakpoints_tablet']['width'] = [
      '#type' => 'number',
      '#title' => 'width',
      '#min' => 1,
      '#default_value' => $publicity_entity->get('width'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $publicity_entity = $this->entity;
    $status = $publicity_entity->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Publicity entity.', [
          '%label' => $publicity_entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Publicity entity.', [
          '%label' => $publicity_entity->label(),
        ]));
    }
    $form_state->setRedirectUrl($publicity_entity->toUrl('collection'));
  }

  public function taxonomy_vocabulary_get_names() {
    $names =& drupal_static(__FUNCTION__);
    if (!isset($names)) {
      $names = [];
      $config_names = \Drupal::configFactory()
        ->listAll('taxonomy_vocabulary.');
      foreach ($config_names as $config_name) {
        $id = substr($config_name, strlen('taxonomy_vocabulary.'));
        $names[$id] = $id;
      }
    }
    return $names;
  }

  public function content_type_get_names() {
    $names =& drupal_static(__FUNCTION__);
    if (!isset($names)) {
      $names = [];
      $config_names = \Drupal::configFactory()
        ->listAll('node_type.');
      foreach ($config_names as $config_name) {
        $id = substr($config_name, strlen('node_type.'));
        $names[$id] = $id;
      }
    }
    return $names;
  }
      
}

// $vocabulary = entity_load('taxonomy_vocabulary', 'MACHINE_NAME')->label();
