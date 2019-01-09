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

    $form['url_up'] = [
      '#type' => 'url',
      '#default_value' => $publicity_entity->get('url_up'),
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
        '2' => 'Sections',
        '3' => 'Articles',
      ],
      '#empty_option' => 'content types',
    ];

    $form['breakpoints'] = [
      '#type' => 'fieldset',
      '#title' => t('RENDERED'), 
    ];

    $form['breakpoints']['height'] = [
      '#type' => 'number',
      '#title' => 'height',
      '#min' => 1,
    ];

    $form['breakpoints']['width'] = [
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

}
