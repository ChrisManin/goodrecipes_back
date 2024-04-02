<?php

namespace Drupal\quantity_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'quantity_default_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "quantity_default_formatter",
 *   label = @Translation("Quantity default formatter"),
 *   field_types = {
 *     "quantity_field_type"
 *   }
 * )
 */
class QuantityDefaultFormatter extends FormatterBase {
  /**
   * Define how the field type is shown.
   *
   * Inside this method, we define how the field type will be displayed.
   *
   * @param \Drupal\Core\Field\FieldItemListInterface $items
   * @param string $langcode
   *
   * @return array
   *   Return a render array for the field values.
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      // Render each item as a string with quantity and unit.
      $elements[$delta] = ['#markup' => $item->quantity . ' ' . $item->unit];
    }

    return $elements;
  }
}