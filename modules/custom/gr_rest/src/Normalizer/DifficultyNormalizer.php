<?php

namespace Drupal\gr_rest\Normalizer;

use Drupal\gr_core\Entity\Difficulty;
use Drupal\serialization\Normalizer\ContentEntityNormalizer;

/**
 * Nomalizes/denormalizes difficulty objets into an array structure.
 */
class DifficultyNormalizer extends ContentEntityNormalizer
{
  /**
   * The interface or class that this Normalizer supports.
   * 
   * @var array
   */
  protected $supportedInterfaceOrClass = Difficulty::class;

  /**
   * {@inheritdoc}
   * @param Difficulty $difficulty
   */
  public function normalize($difficulty, $format = NULL, array $context = array()): array|bool|string|int|float|null|\ArrayObject
  {
    return $difficulty->getRest();
  }
}