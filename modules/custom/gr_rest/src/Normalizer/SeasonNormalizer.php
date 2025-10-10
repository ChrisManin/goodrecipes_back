<?php

namespace Drupal\gr_rest\Normalizer;

use Drupal\gr_core\Entity\Season;
use Drupal\serialization\Normalizer\ContentEntityNormalizer;

/**
 * Normalizes/denormalizes season objects into an array structure.
 */
class SeasonNormalizer extends ContentEntityNormalizer
{
  /**
   * The interface or class that this Normalizer supports.
   *
   * @var array
   */
  protected $supportedInterfaceOrClass = Season::class;

  /**
   * {@inheritdoc}
   * @param Season $season
   */
  public function normalize($season, $format = NULL, array $context = array()): array|bool|string|int|float|null|\ArrayObject
  {
    return $season->getRest();
  }
}