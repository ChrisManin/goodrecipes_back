<?php

namespace Drupal\gr_core\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\EntityReferenceFieldItemListInterface;
use Drupal\gr_core\Entity\AbstractNode;

class Ingredient extends AbstractNode {

  /**
   * {@inheritdoc}
   */
  public static function postDelete(EntityStorageInterface $storage, array $entities) {
    
    /** @var Ingredient $ingredient */
    foreach ($entities as $ingredient) {
      // Delete attached visual file
      $visual = $ingredient->getIngredientVisualFile();
      $visual?->delete();
    }
  }

  /**
   * Return ingredient infos
   * @return array
   * @throws MissingDataException
   */
  public function getRest(array $fields = [
    'id',
    'type',
    'title',
    'alias',
    'visual',
  ]): array
  {
    $rest = [];

    foreach ($fields as $field) {
      $data = NULL;
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
        case 'alias':
          $data = $this->getAlias();
          break;
        case 'visual':
          $data = $this->getRestVisual();
          break;
      }
      if ($data) {
        $rest[$field] = $data;
      }
    }
    return $rest;
  }

  public function getIngredientVisualFile(string $field_name='field_ingredient_visual'): ?File {
    return parent::getVisualFile($field_name);
  }

  public function getCustomType(){ return $this->bundle(); }

  /**
   * Return show visual link and alt text
   *
   * @param $imageStyle
   * @param string $label
   * @return string[]
   * @throws MissingDataException
   */
  public function getRestVisual(string $field_name='field_ingredient_visual'): array {
    $visual = parent::getRestVisual($field_name);
    return $visual;
  }
}