<?php

namespace Drupal\quantity_field\Plugin\Field\FieldWidget;

use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'quantity_default_widget' widget.
 *
 * @FieldWidget(
 *   id = "quantity_default_widget",
 *   label = @Translation("Quantity default widget"),
 *   field_types = {
 *     "quantity_field_type"
 *   }
 * )
 */
class QuantityDefaultWidget extends WidgetBase {
  /**
   * Define the form for the field type.
   *
   * Inside this method, we define the form used to edit the field.
   *
   * @param \Drupal\Core\Field\FieldItemListInterface $items
   * @param int $delta
   * @param array $element
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element['ingredient_id'] = [
      '#type' => 'entity_autocomplete',
      '#title' => t('Ingrédient'),
      '#target_type' => 'node',
      '#selection_handler' => 'default',
      '#selection_settings' => ['target_bundles' => ['ingredient']],
      '#default_value' => isset($items[$delta]->ingredient_id) ? \Drupal\node\Entity\Node::load($items[$delta]->ingredient_id) : NULL,
      '#required' => FALSE,
    ];

    $element['quantity'] = [
      '#type' => 'number',
      '#title' => t('Quantité'),
      '#default_value' => isset($items[$delta]->quantity) ? $items[$delta]->quantity : NULL,
      '#min' => 0,
      '#required' => FALSE,
    ];

    $element['unit'] = [
      '#type' => 'select',
      '#title' => t('Unité'),
      '#options' => $this->getUnitOptions(),
      '#default_value' => isset($items[$delta]->unit) ? $items[$delta]->unit : NULL,
      '#required' => FALSE,
    ];

    return $element;
  }

  /**
   * Generate the list of options for the unit dropdown.
   *
   * @return array
   */
  protected function getUnitOptions() {
    $options = [];
    // Load the taxonomy vocabulary containing units.
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'measurement']);
    foreach ($terms as $term) {
      $options[$term->id()] = $term->get('field_measurement_abbreviation')->value;
    }
    return $options;
  }
}