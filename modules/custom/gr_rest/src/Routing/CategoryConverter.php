<?php

namespace Drupal\gr_rest\Routing;

use Drupal\Core\ParamConverter\ParamConverterInterface;
use Drupal\Core\Url;
use Drupal\gr_core\Entity\Category;
use Symfony\Component\Routing\Route;

class CategoryConverter implements ParamConverterInterface {
  /**
   * {@inheritdoc}
   */
  public function applies($definition, $name, Route $route): bool {
    return !empty($definition['type']) && $definition['type'] == 'entity:taxonomy_term' && $definition['vid'] == 'category';
  }

   /**
   * {@inheritdoc}
   */
  public function convert($value, $definition, $name, array $defaults): ?Category {
    return $this->getTermByAlias($value);
  }

  private function getTermByAlias($alias) {
    $path = \Drupal::service('path_alias.manager')->getPathByAlias('/' . $alias);
    if ($path == $alias) {
      return NULL;
    }
    $params = Url::fromUri("internal:" . $path)->getRouteParameters();
    $entity_type = key($params);
    return \Drupal::entityTypeManager()->getStorage($entity_type)->load($params[$entity_type]);
  }
}