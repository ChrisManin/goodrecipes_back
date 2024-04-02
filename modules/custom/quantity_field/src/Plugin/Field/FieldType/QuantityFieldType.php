<?php

namespace Drupal\quantity_field\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'quantity_field_type' field type.
 *
 * @FieldType(
 *   id = "quantity_field_type",
 *   label = @Translation("Quantity field"),
 *   description = @Translation("A field for ingredients with quantity and unit."),
 *   default_widget = "quantity_default_widget",
 *   default_formatter = "quantity_default_formatter"
 * )
 */
class QuantityFieldType extends FieldItemBase {
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['quantity'] = DataDefinition::create('integer')
      ->setLabel(t('Quantity'));

    $properties['unit'] = DataDefinition::create('string')
      ->setLabel(t('Unit'));

    return $properties;
  }

  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'quantity' => [
          'type' => 'int',
          'not null' => TRUE,
        ],
        'unit' => [
          'type' => 'varchar',
          'length' => 255,
          'not null' => TRUE,
        ],
      ],
    ];
  }

  public function isEmpty() {
    $quantity = $this->get('quantity')->getValue();
    $unit = $this->get('unit')->getValue();
    return empty($quantity) && empty($unit);
  }
}