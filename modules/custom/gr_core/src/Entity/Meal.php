<?php

namespace Drupal\gr_core\Entity;

class Meal extends AbstractNode
{
  public function getRest(array $fields = [
    'id', 'type', 'title', 'alias', 'visual',
    'description', 'category', 'season', 'starter', 'main_dish', 'dessert',
    'date', 'meal_type',
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
          $data = $this->getMealRestVisual();
          break;
        case 'description':
          $data = $this->getDescription();
          break;
        case 'category':
          $data = $this->getMealCategory()?->getRest();
          break;
        case 'season':
          $data = $this->getMealSeason()?->getRest();
          break;
        case 'starter':
          $data = $this->getStarter()?->getRest(['id', 'type', 'title', 'alias', 'visual']);
          break;
        case 'main_dish':
          $data = $this->getMainDish()?->getRest(['id', 'type', 'title', 'alias', 'visual']);
          break;
        case 'dessert':
          $data = $this->getDessert()?->getRest(['id', 'type', 'title', 'alias', 'visual']);
          break;
        case 'date':
          $data = $this->getMealDate();
          break;
        case 'meal_type':
          $data = $this->getMealType();
          break;
      }
      if ($data) {
        $rest[$field] = $data;
      }
    }

    return $rest;
  }

  public function getMealRestVisual(): array
  {
    return $this->getRestVisual('field_meal_visual');
  }

  public function getDescription(): ?string
  {
    if (!$this->get('field_meal_description')->isEmpty()) {
      return $this->get('field_meal_description')->value;
    }
    return NULL;
  }

  public function getMealCategory(): ?Category
  {
    return $this->field_meal_category?->first()?->get('entity')?->getTarget()?->getValue();
  }

  public function getMealSeason(): ?Season
  {
    return $this->field_meal_season?->first()?->get('entity')?->getTarget()?->getValue();
  }

  public function getStarter(): ?Recipe
  {
    return $this->field_meal_starter?->first()?->get('entity')?->getTarget()?->getValue();
  }

  public function getMainDish(): ?Recipe
  {
    return $this->field_meal_main_dish?->first()?->get('entity')?->getTarget()?->getValue();
  }

  public function getDessert(): ?Recipe
  {
    return $this->field_meal_dessert?->first()?->get('entity')?->getTarget()?->getValue();
  }

  public function getMealDate(): ?string
  {
    if (!$this->get('field_meal_date')->isEmpty()) {
      return $this->get('field_meal_date')->value;
    }
    return NULL;
  }

  public function getMealType(): ?string
  {
    if (!$this->get('field_meal_type')->isEmpty()) {
      return $this->get('field_meal_type')->value;
    }
    return NULL;
  }
}
