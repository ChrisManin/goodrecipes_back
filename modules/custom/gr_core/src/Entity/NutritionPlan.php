<?php

namespace Drupal\gr_core\Entity;

class NutritionPlan extends AbstractNode
{
  public function getRest(array $fields = [
    'id', 'type', 'title', 'alias', 'visual',
    'description', 'start_date', 'end_date', 'meals',
  ]): array {
    $rest = [];

    foreach ($fields as $field) {
      $data = NULL;
      switch ($field) {
        case 'id':
          $data = $this->id();
          break;
        case 'type':
          $data = $this->bundle();
          break;
        case 'title':
          $data = $this->getTitle();
          break;
        case 'alias':
          $data = $this->getAlias();
          break;
        case 'visual':
          $data = $this->getNutritionPlanRestVisual();
          break;
        case 'description':
          $data = $this->getDescription();
          break;
        case 'start_date':
          $data = $this->getStartDate();
          break;
        case 'end_date':
          $data = $this->getEndDate();
          break;
        case 'meals':
          $data = $this->getMeals();
          break;
      }
      if ($data) {
        $rest[$field] = $data;
      }
    }

    return $rest;
  }

  public function getNutritionPlanRestVisual(): array
  {
    return $this->getRestVisual('field_nutrition_plan_visual');
  }

  public function getDescription(): ?string
  {
    if (!$this->get('field_nutrition_plan_description')->isEmpty()) {
      return $this->get('field_nutrition_plan_description')->value;
    }
    return NULL;
  }

  public function getStartDate(): ?string
  {
    if (!$this->get('field_nutrition_plan_start_date')->isEmpty()) {
      return $this->get('field_nutrition_plan_start_date')->value;
    }
    return NULL;
  }

  public function getEndDate(): ?string
  {
    if (!$this->get('field_nutrition_plan_end_date')->isEmpty()) {
      return $this->get('field_nutrition_plan_end_date')->value;
    }
    return NULL;
  }

  public function getMeals(): ?array
  {
    $meals = [];
    foreach ($this->get('field_nutrition_plan_meals') as $item) {
      $meal = $item->get('entity')?->getTarget()?->getValue();
      if ($meal) {
        $meals[] = $meal->getRest();
      }
    }
    return $meals ?: NULL;
  }
}
