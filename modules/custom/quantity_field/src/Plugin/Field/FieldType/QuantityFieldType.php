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
    $properties['ingredient_id'] = DataDefinition::create('integer')
      ->setLabel(t('Ingredient ID'))
      ->setRequired(TRUE);

    $properties['quantity'] = DataDefinition::create('integer')
      ->setLabel(t('Quantity'))
      ->setRequired(FALSE);

    $properties['unit'] = DataDefinition::create('string')
      ->setLabel(t('Unit'))
      ->setRequired(FALSE);

    return $properties;
  }

  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'ingredient_id' => [
          'type' => 'int',
          'not null' => TRUE,
        ],
        'quantity' => [
          'type' => 'int',
          'not null' => FALSE,
        ],
        'unit' => [
          'type' => 'varchar',
          'length' => 255,
          'not null' => FALSE,
        ],
      ],
      'indexes' => [
        'ingredient_id' => ['ingredient_id'],
      ],
    ];
  }

  public function isEmpty() {
    $ingredient_id = $this->get('ingredient_id')->getValue();
    return empty($ingredient_id);
  }
}