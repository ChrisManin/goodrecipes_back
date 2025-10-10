<?php

namespace Drupal\gr_core\Entity;

use Drupal\Core\Render\RenderContext;
use Drupal\taxonomy\Entity\Term;

abstract class AbstractTerm extends Term
{
  /**
   * Return alias
   * @return string
   */
  public function getAlias(): ?string
  {
    if (!$this->get('path')->isEmpty()) {
      return $this->get('path')->getValue()[0]['alias'];
    }
    return NULL;
  }
}