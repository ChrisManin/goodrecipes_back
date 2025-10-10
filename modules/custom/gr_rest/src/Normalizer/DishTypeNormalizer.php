<?php

namespace Drupal\gr_rest\Normalizer;

use Drupal\gr_core\Entity\DishType;
use Drupal\serialization\Normalizer\ContentEntityNormalizer;

/**
 * Normalizes/denormalizes dish type objects into an array structure.
 */
class DishTypeNormalizer extends ContentEntityNormalizer
{
  /**
   * The interface or class that this Normalizer supports.
   *
   * @var array
   */
  protected $supportedInterfaceOrClass = DishType::class;

  /**
   * {@inheritdoc}
   * @param DishType $dish_type
   */
  public function normalize($dish_type, $format = NULL, array $context = array()): array|bool|string|int|float|null|\ArrayObject
  {
    return $dish_type->getRest();
  }
}