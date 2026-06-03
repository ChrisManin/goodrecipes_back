<?php

namespace Drupal\gr_rest\Normalizer;

use Drupal\gr_core\Entity\NutritionPlan;
use Drupal\serialization\Normalizer\ContentEntityNormalizer;

class NutritionPlanNormalizer extends ContentEntityNormalizer
{
  protected $supportedInterfaceOrClass = NutritionPlan::class;

  public function normalize($nutrition_plan, $format = NULL, array $context = []): array|bool|string|int|float|null|\ArrayObject
  {
    return $nutrition_plan->getRest();
  }
}
