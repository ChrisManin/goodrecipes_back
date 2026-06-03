<?php

namespace Drupal\gr_rest\Normalizer;

use Drupal\gr_core\Entity\Measurement;
use Drupal\serialization\Normalizer\ContentEntityNormalizer;

class MeasurementNormalizer extends ContentEntityNormalizer
{
  protected $supportedInterfaceOrClass = Measurement::class;

  public function normalize($measurement, $format = NULL, array $context = []): array|bool|string|int|float|null|\ArrayObject
  {
    return $measurement->getRest();
  }
}
