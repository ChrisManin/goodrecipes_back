<?php

namespace Drupal\gr_rest\Plugin\rest\resource;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\gr_rest\Finder\HomePageFinder;
use Drupal\gr_rest\Service\SeasonResolver;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @RestResource(
 *   id = "gr_homepage",
 *   label = @Translation("GR Homepage"),
 *   uri_paths = {
 *     "canonical" = "/api/homepage"
 *   }
 * )
 */
class HomePageResource extends ResourceBase {

  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    protected HomePageFinder $finder
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): static {
    $entityTypeManager = $container->get('entity_type.manager');
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('gr_rest'),
      new HomePageFinder($entityTypeManager, new SeasonResolver($entityTypeManager))
    );
  }

  public function get(): ResourceResponse {
    $suggestions = $this->finder->getSuggestions();
    $suggestionIds = array_column($suggestions, 'id');

    $data = [
      'suggestions'       => $suggestions,
      'seasonal_recipes'  => $this->finder->getSeasonalRecipes($suggestionIds),
      'categories'        => $this->finder->getCategories(),
      'thermomix_recipes' => $this->finder->getThermomixRecipes(),
      'weekly_menu'       => $this->finder->getWeeklyMenu(),
    ];

    $response = new ResourceResponse($data, 200);
    $response->addCacheableDependency(
      (new CacheableMetadata())
        ->setCacheMaxAge(86400)
        ->addCacheTags(['node_list', 'taxonomy_term_list'])
    );
    return $response;
  }

}
