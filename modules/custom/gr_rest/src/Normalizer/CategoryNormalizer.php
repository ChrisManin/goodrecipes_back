<?php

namespace Drupal\gr_rest\Normalizer;

use Drupal\gr_core\Entity\Category;
use Drupal\serialization\Normalizer\ContentEntityNormalizer;

/**
 * Normalizes/denormalizes catgory objects into an array structure.
 */
class CategoryNormalizer extends ContentEntityNormalizer
{
  /**
   * The interface or class that this Normalizer supports.
   *
   * @var array
   */
  protected $supportedInterfaceOrClass = Category::class;

  /**
   * {@inheritdoc}
   * @param Category $category
   */
  public function normalize($category, $format = NULL, array $context = array()): array|bool|string|int|float|null|\ArrayObject
  {
    return $category->getRest();
  }
}