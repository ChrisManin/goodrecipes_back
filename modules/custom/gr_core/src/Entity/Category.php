<?php

namespace Drupal\gr_core\Entity;

use Drupal\taxonomy\Entity\Term;

class Category extends Term
{
  public function getRest(): array
  {
    $rest = [
      'id' => $this->id(),
      'name' => $this->getName(),
    ];

    return $rest;
  }
}