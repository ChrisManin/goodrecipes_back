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
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    foreach ($items as $delta => $item) {
      $ingredient = \Drupal\node\Entity\Node::load($item->ingredient_id);
      $elements[$delta] = [
        '#markup' => $ingredient->getTitle() . ' : ' . $item->quantity . ' ' . $item->unit,
      ];
    }
    return $elements;
  }
}