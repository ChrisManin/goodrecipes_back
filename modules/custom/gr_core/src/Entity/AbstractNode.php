<?php

namespace Drupal\gr_core\Entity;

use Drupal\Core\Url;
use Drupal\node\Entity\Node;

abstract class AbstractNode extends Node
{
  public function getVisualFile(string $field_name): ?File {
    return $this->$field_name
    ?->first()
    ?->get('entity')
    ?->getTarget()
    ?->getValue();
  }

  public function getRestVisual(string $field_name): array
  {
    $rest = [
      "url" => '',
      "alt" => '',
    ];

    $visual = $this->getVisualFile($field_name);

    if ($visual) {
      $rest["url"] = $visual->buildImageStyleUri();
      $rest["alt"] = $this->get($field_name)->first()->get('alt')->getValue();
    }

    return $rest;
  }

  /**
   * Return node alias
   * @return string
   */
  public function getAlias(): string {
    if (!empty($this->get('path')->getValue()[0]['alias'])) {
      return $this->get('path')->getValue()[0]['alias'];
    }
    return '';
  }

  public static function getNodeByAlias($alias) {
    $path = \Drupal::service('path_alias.manager')->getPathByAlias('/' . $alias);
    if ($path == $alias) {
      return NULL;
    }
    $params = Url::fromUri("internal:" . $path)->getRouteParameters();
    $entity_type = key($params);
    return \Drupal::entityTypeManager()->getStorage($entity_type)->load($params[$entity_type]);
  }

  /**
   * Return the category
   *
   * @return ?Category
   */
  public function getCategory(): ?Category
  {
    /** @var \Drupal\gr_core\Entity\Category */
    $category = $this
      ?->field_category
      ?->first()
      ?->get('entity')
      ?->getTarget()
      ?->getValue();

    return $category;
  }
}