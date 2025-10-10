<?php

namespace Drupal\gr_core\Entity;

use Drupal\taxonomy\Entity\Term;

class Difficulty extends AbstractTerm
{
  public function getRest(): array
  {
    $rest = [
      'id' => $this->id(),
      'name' => $this->getName(),
      'type' => $this->bundle(),
      'alias' => $this->getAlias(),
    ];

    return $rest;
  }
}