<?php

namespace Drupal\gr_core\Entity;

use Drupal\taxonomy\Entity\Term;

class DishType extends AbstractTerm
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