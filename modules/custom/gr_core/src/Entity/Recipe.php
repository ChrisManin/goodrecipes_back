<?php

namespace Drupal\gr_core\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\EntityReferenceFieldItemListInterface;
use Drupal\gr_core\Entity\AbstractNode;
use Drupal\Core\Datetime\DrupalDateTime;

class Recipe extends AbstractNode {

  /**
   * {@inheritdoc}
   */
  public static function postDelete(EntityStorageInterface $storage, array $entities) {

    /** @var Recipe $recipe */
    foreach ($entities as $recipe) {
      // Delete attached visual file
      $visual = $recipe->getRecipeVisualFile();
      $visual?->delete();
    }
  }

  /**
   * Return recipe infos
   * @return array
   * @throws MissingDataException
   */
  public function getRest(array $fields = [
    'id',
    'type',
    'title',
    'created',
    'alias',
    'visual',
    'caloric_intake',
    'category',
    'cooking_duration',
    'prepare_duration',
    'description',
    'difficulty',
    'ingredients',
    'notes',
    'preparation',
    'servings',
    'source',
    'tags',
    'dish_type'
  ]): array
  {
    $rest = [];

    foreach ($fields as $field) {
      $data =NULL;
      switch ($field) {
        case 'id':
          $data = $this->id();
          break;
        case 'type':
          $data = $this->getCustomType();
          break;
        case 'title':
          $data = $this->getTitle();
          break;
        case 'created':
          $data = $this->getCreatedDate();
          break;
        case 'alias':
          $data = $this->getAlias();
          break;
        case 'visual':
          $data = $this->getRestVisual();
          break;
        case 'caloric_intake':
          $data = $this->getCaloricIntake();
          break;
        case 'category':
          $data = $this->getCategory();
          break;
        case 'cooking_duration':
          $data = $this->getCookingDuration();
          break;
        case 'prepare_duration':
          $data = $this->getPrepareDuration();
          break;
        case 'description':
          $data = $this->getDescription();
          break;
        case 'difficulty':
          $data = $this->getDifficulty();
          break;
        case 'ingredients':
          $data = $this->getIngredients();
          break;
        case 'notes':
          $data = $this->getNotes();
          break;
        case 'preparation':
          $data = $this->getPreparation();
          break;
        case 'servings':
          $data = $this->getServings();
          break;
        case 'source':
          $data = $this->getSource();
          break;
        case 'tags':
          $data = $this->getTags();
          break;
        case 'dish_type':
          $data = $this->getDishType();
          break;
      }
      if ($data) {
        $rest[$field] = $data;
      }
    }
    return $rest;
  }

  public function getRecipeVisualFile(string $field_name='field_recipe_visual'): ?File {
    return parent::getVisualFile($field_name);
  }

  public function getCustomType(){ return $this->bundle(); }

  /**
   * Return recipe created date
   */
  public function getCreatedDate() {
    $created = DrupalDateTime::createdFromTimestamp($this->getCreatedTime());
    return $created->format('j F Y', ['langcode' => 'fr']);
    return NULL;
  }

  /**
   * Return recipe visual link and alt text
   *
   * @param $imageStyle
   * @param string $label
   * @return string[]
   * @throws MissingDataException
   */
  public function getRestVisual(string $field_name='field_recipe_visual'): array {
    $visual = parent::getRestVisual($field_name);
    return $visual;
  }

  public function getCaloricIntake() {
    if (!$this->get('field_recipe_caloric_intake')->isEmpty()) {
      return $this->get('field_recipe_caloric_intake')->value;
    }
    return NULL;
  }

  public function getCookingDuration() {
    
  }

  public function getPrepareDuration() {
    
  }

  public function getDescription() {
    
  }

  public function getDifficulty() {
    
  }

  public function getIngredients() {
    
  }

  public function getNotes() {
    
  }

  public function getPreparation() {
    
  }

  public function getServings() {
    
  }

  public function getSource() {
    
  }

  public function getTags() {
    
  }

  public function getDishType() {
    
  }

}