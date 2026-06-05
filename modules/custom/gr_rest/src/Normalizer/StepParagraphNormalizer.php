<?php

namespace Drupal\gr_rest\Normalizer;

use Drupal\gr_core\Entity\StepParagraph;
use Drupal\paragraphs\ParagraphInterface;
use Drupal\serialization\Normalizer\ContentEntityNormalizer;

class StepParagraphNormalizer extends ContentEntityNormalizer
{
  protected $supportedInterfaceOrClass = StepParagraph::class;

  public function supportsNormalization($data, $format = NULL, array $context = []): bool
  {
    return $data instanceof ParagraphInterface && $data->bundle() === 'step_paragraph';
  }

  public function normalize($paragraph, $format = NULL, array $context = []): array|bool|string|int|float|null|\ArrayObject
  {
    return $paragraph->getRest();
  }
}
