<?php

namespace Drupal\publicity\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Number as NumberUtility;
use Symfony\Component\DependencyInjection\ContainerInterface;

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

    $class = get_class($this);
    $publicity_entity = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#maxlength' => 255,
      '#default_value' => $publicity_entity->label,
      '#description' => $this->t("Name for the Publicity."),
      '#required' => TRUE,
      '#element_validate'=>[
        [$class, 'validateString'],
      ],
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
        '#element_validate'=>[
          [$class, 'validateNumber'],
        ],
      ];
  
      $form['breakpoints']['form'][$delta]['height'] = [
        '#type' => 'number',
        '#title' => 'height', 
        '#min' => 1,
        '#required' => TRUE,
        '#default_value' => $width['form'][$delta]['height'],
        '#description' => $this->t('The height in px.'),
        '#element_validate'=>[
          [$class, 'validateNumber'],
        ],
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
   * 
   */
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

  public static function validateNumber(&$element, FormStateInterface $form_state, &$complete_form) {
    $value = $element['#value'];
    if ($value === '') {
      return;
    }
    $name = empty($element['#title']) ? $element['#parents'][0] : $element['#title'];
    // Ensure the input is numeric.
    if (!is_numeric($value)) {
      $form_state->setError($element, t('%name must be a number.', ['%name' => $name]));
      return;
    }
    // Ensure that the input is greater than the #min property, if set.
    if (isset($element['#min']) && $value < $element['#min']) {
      $form_state->setError($element, t('%name must be higher than or equal to %min.', ['%name' => $name, '%min' => $element['#min']]));
    }
    // Ensure that the input is less than the #max property, if set.
    if (isset($element['#max']) && $value > $element['#max']) {
      $form_state->setError($element, t('%name must be lower than or equal to %max.', ['%name' => $name, '%max' => $element['#max']]));
    }
    if (isset($element['#step']) && strtolower($element['#step']) != 'any') {
      // Check that the input is an allowed multiple of #step (offset by #min if
      // #min is set).
      $offset = isset($element['#min']) ? $element['#min'] : 0.0;
      if (!NumberUtility::validStep($value, $element['#step'], $offset)) {
        $form_state->setError($element, t('%name is not a valid number.', ['%name' => $name]));
      }
    }
  }
  /**
   * {@inheritdoc}
   */
  public static function validateString(&$element, FormStateInterface $form_state, &$complete_form) {
    $value = $element['#value'];
    $value = strtolower($value);
    if (!preg_match('/^[a-z ]{3,15}$/', $value)) {
      $form_state->setError($element, t('Please. Write only data type string. Minimum 3 characters and Maximum 15'));
    }
  }
  public static function validateIdpublicity(&$element, FormStateInterface $form_state, &$complete_form){
    $value = $element['#value'];
    $value = strtolower($value);
    if (!preg_match('/^[a-z0-9]{6}$/', $value)){
      $form_state->setError($element, t('Please. Write only data type string. Minimum 3 characters and Maximum 6'));
    }
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

  /**
   * @return $names = taxonomy Vocabulary added
   */
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

  /**
   * @return $names = content types added
   */
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