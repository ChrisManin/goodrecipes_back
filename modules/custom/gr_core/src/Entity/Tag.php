<?php

namespace Drupal\gr_core\Entity;

class Tag extends AbstractTerm
{
  public function getRest(): array
  {
    return [
      'id' => $this->id(),
      'name' => $this->getName(),
      'type' => $this->bundle(),
      'alias' => $this->getAlias(),
    ];
  }
}
