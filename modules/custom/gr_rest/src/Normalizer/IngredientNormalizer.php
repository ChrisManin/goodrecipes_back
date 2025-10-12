<?php

namespace Drupal\gr_rest\Normalizer;

use Drupal\serialization\Normalizer\ContentEntityNormalizer;
use Drupal\gr_core\Entity\Ingredient;

/**
 * Normalizes/denormalizes ingredient objects into an array structure.
 */
class IngredientNormalizer extends ContentEntityNormalizer {
  /**
   * The interface or class that this Normalizer supports.
   *
   * @var array
   */
  protected $supportedInterfaceOrClass = Ingredient::class;

  /**
   * {@inheritdoc}
   * @param Ingredient $ingredient
   */
  public function normalize($ingredient, $format = NULL, array $context = array()): array|bool|string|int|float|null|\ArrayObject {
    return $ingredient->getRest();
  }
}
