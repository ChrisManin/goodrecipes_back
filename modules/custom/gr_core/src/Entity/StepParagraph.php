<?php

namespace Drupal\gr_core\Entity;

use Drupal\paragraphs\Entity\Paragraph;

class StepParagraph extends Paragraph
{
  public function getRest(): array
  {
    return [
      'id'          => $this->id(),
      'description' => $this->getDescription(),
    ];
  }

  public function getDescription(): ?string
  {
    if (!$this->get('field_step_paragraph_description')->isEmpty()) {
      return $this->get('field_step_paragraph_description')->value;
    }
    return NULL;
  }
}
