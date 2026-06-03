<?php

namespace Drupal\gr_core\Entity;

use Drupal\paragraphs\Entity\Paragraph;

class IngredientParagraph extends Paragraph
{
  public function getRest(): array
  {
    $rest = [
      'id' => $this->id(),
      'ingredient' => $this->getParagraphIngredient()?->getRest(),
    ];

    $quantity = $this->getQuantity();
    if ($quantity !== NULL) {
      $rest['quantity'] = $quantity;
    }

    $unit = $this->getUnit();
    if ($unit !== NULL) {
      $rest['unit'] = $unit;
    }

    return $rest;
  }

  public function getParagraphIngredient(): ?Ingredient
  {
    return $this->field_ingredient_para_ingredient
      ?->first()
      ?->get('entity')
      ?->getTarget()
      ?->getValue();
  }

  public function getQuantity(): ?int
  {
    if (!$this->get('field_ingredient_para_quantity')->isEmpty()) {
      return (int) $this->get('field_ingredient_para_quantity')->first()->quantity;
    }
    return NULL;
  }

  public function getUnit(): ?array
  {
    if (!$this->get('field_ingredient_para_quantity')->isEmpty()) {
      $unit_id = $this->get('field_ingredient_para_quantity')->first()->unit;
      if ($unit_id) {
        $measurement = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($unit_id);
        return $measurement?->getRest();
      }
    }
    return NULL;
  }
}
