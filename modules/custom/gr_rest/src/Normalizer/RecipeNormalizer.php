<?php

namespace Drupal\gr_rest\Normalizer;

use Drupal\serialization\Normalizer\ContentEntityNormalizer;
use Drupal\gr_core\Entity\Recipe;

/**
 * Normalizes\denormalizes recipe objects into an array structure.
 */
class RecipeNormalizer extends ContentEntityNormalizer {
  /**
   * The interface or class that this Normalizer supports.
   * 
   * @var array
   */
  protected $supportedInterfaceOrClass = Recipe::class;

  /**
   * {@inheritdoc}
   * @param Recipe $recipe
   */
  public function normalize($recipe, $format = NULL, array $context = array()): array|bool|string|int|float|null|\ArrayObject {
    return $recipe->getRest();
  }
}