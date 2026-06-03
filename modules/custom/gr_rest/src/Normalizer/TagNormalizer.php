<?php

namespace Drupal\gr_rest\Normalizer;

use Drupal\gr_core\Entity\Tag;
use Drupal\serialization\Normalizer\ContentEntityNormalizer;
use Drupal\taxonomy\TermInterface;

class TagNormalizer extends ContentEntityNormalizer
{
  protected $supportedInterfaceOrClass = Tag::class;

  public function supportsNormalization($data, $format = NULL, array $context = []): bool {
    return $data instanceof TermInterface && $data->bundle() === 'tags';
  }

  public function normalize($tag, $format = NULL, array $context = []): array|bool|string|int|float|null|\ArrayObject
  {
    return $tag->getRest();
  }
}
