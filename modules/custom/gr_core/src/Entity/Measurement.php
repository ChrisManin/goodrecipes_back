<?php

namespace Drupal\gr_core\Entity;

class Measurement extends AbstractTerm
{
  public function getRest(): array
  {
    return [
      'id' => $this->id(),
      'name' => $this->getName(),
      'abbreviation' => $this->get('field_measurement_abbreviation')->value,
      'type' => $this->bundle(),
    ];
  }
}
