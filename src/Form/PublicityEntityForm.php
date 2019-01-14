<?php

namespace Drupal\publicity\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\core\modules\taxonomy\src\Entity\Vocabulary;

/**
 * Class PublicityEntityForm.
 */
class PublicityEntityForm extends EntityForm {

  protected $name;

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
      '#default_value' => $publicity_entity->label,
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

    $form[] = [
      '#type' => 'textfield',
      '#title' => $this->t('Identificator'),
      '#description' => $this->t("ID the publicity"),
      '#placeholder' => '8c4...'

    ];

    $form['UrlPu'] = [
      '#type' => 'url',
      '#default_value' => $publicity_entity->getUrlPu(),
      '#title' => $this->t('Url publicity'),
      '#description' => $this->t("Url the publicity"),
      '#placeholder' => 'https://'
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

    $form['breakpoints'] = [
      '#type' => 'fieldset',
      '#tree' => TRUE,
      '#description' => '',
      '#title' => $this->t('Breakpoints'),
      '#prefix' => '<div id="breakpoint-wrapper">',
      '#suffix' => '</div>',
    ];

    $width = $publicity_entity->getBreakpoints();
    if(!empty($width)) {
      $form_state->set('field_deltas', range(0,count($width['form']) - 1));
    }
    if ($form_state->get('field_deltas') == '') {
      $form_state->set('field_deltas', range(0,1));
    }
    
    $field_count = $form_state->get('field_deltas');
    $names_options = ['- Desktop', '- Tablet', '- Mobile', '- Other'];
  
    foreach ($field_count as $delta) {
      $form['breakpoints']['form'][$delta] = [
        '#type' => 'fieldset',
        '#title' => $this->t('Options ' . $names_options[$delta]),
        '#tree' => TRUE,
      ];
  
      $form['breakpoints']['form'][$delta]['width'] = [
        '#type' => 'number',
        '#title' => 'Width', 
        '#min' => 1,
        '#required' => TRUE,
        '#default_value' => $width['form'][$delta]['width'],
        '#description' => $this->t('The width in px.'),
      ];
  
      $form['breakpoints']['form'][$delta]['height'] = [
        '#type' => 'number',
        '#title' => 'height', 
        '#min' => 1,
        '#required' => TRUE,
        '#default_value' => $width['form'][$delta]['height'],
        '#description' => $this->t('The height in px.'),
      ];
  
      $form['breakpoints']['form'][$delta]['remove'] = [
        '#type' => 'submit',
        '#value' => $this->t('Remove'),
        '#submit' => ['::addMoreRemove'],
        '#ajax' => [
          'callback' => '::addMoreRemoveCallback',
          'wrapper' => 'breakpoint-wrapper',
        ],
        '#name' => 'remove_name_' . $delta,
      ];
    }
    $form['breakpoints']['add'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add'),
      '#submit' => ['::AddMoreAddOne'],
      '#ajax' => [
        'callback' => '::AddMoreAddOneCallback',
        'wrapper' => 'breakpoint-wrapper',
      ],
    ];
    return $form;
  }
  /**
   * function to add one field of breakpoint.
   * 
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
	public function addMoreRemove(array &$form, FormStateInterface $form_state) {
		// Get the triggering item
    $delta_remove = $form_state->getTriggeringElement()['#parents'][2];
    
    // Store our form state
    $field_deltas_array = $form_state->get('field_deltas');
    
    // Find the key of the item we need to remove
    $key_to_remove = array_search($delta_remove, $field_deltas_array);
    
    // Remove our triggered element
    unset($field_deltas_array[$key_to_remove]);
    
    // Rebuild the field deltas values
    $form_state->set('field_deltas', $field_deltas_array);
    
    // Rebuild the form
    $form_state->setRebuild();
    return $this->messenger()->addMessage($this->t('The BreakPoint has been remove'), 'warning');
	}
  /**
   * ajax callback to add the new field to the render form.
   * 
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return mixed
   */
  public function addMoreRemoveCallback(array &$form, FormStateInterface $form_state) {
		return $form['breakpoints'];
	}
  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
	public function AddMoreAddOne(array &$form, FormStateInterface $form_state) {
    // Store our form state
    $field_deltas_array = $form_state->get('field_deltas');
    
    // check to see if there is more than one item in our array
    if (count($field_deltas_array) > 0) {
      // Add a new element to our array and set it to our highest value plus one
      $field_deltas_array[] = max($field_deltas_array) + 1;
    }
    else {
      // Set the new array element to 0
      $field_deltas_array[] = 0;
    }
  
    // Rebuild the field deltas values
    $form_state->set('field_deltas', $field_deltas_array);
  
    // Rebuild the form
    $form_state->setRebuild();
    
  }
  
  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return mixed
   */
  function AddMoreAddOneCallback(array &$form, FormStateInterface $form_state) {
    return $form['breakpoints'];
  }  


  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $publicity_entity = $this->entity;
    $value_breakpoints= $form_state->getValue('breakpoints', 'form');
    $publicity_entity->setBreakpoints($value_breakpoints);
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
        ->listAll('taxonomy.vocabulary.');
      foreach ($config_names as $config_name) {
        $id = substr($config_name, strlen('taxonomy.vocabulary.'));
        $names[$id] = strtolower(entity_load('taxonomy_vocabulary', $id)->label());
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
        $names[$id] = strtolower(entity_load('node_type', $id)->label());
        
      }
    }
    return $names;
  }
      
}

// $vocabulary = entity_load('taxonomy_vocabulary', 'MACHINE_NAME')->label();
