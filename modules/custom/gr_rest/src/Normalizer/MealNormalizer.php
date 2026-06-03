<?php

namespace Drupal\gr_rest\Normalizer;

use Drupal\gr_core\Entity\Meal;
use Drupal\serialization\Normalizer\ContentEntityNormalizer;

class MealNormalizer extends ContentEntityNormalizer
{
  protected $supportedInterfaceOrClass = Meal::class;

  public function normalize($meal, $format = NULL, array $context = []): array|bool|string|int|float|null|\ArrayObject
  {
    return $meal->getRest();
  }
}
