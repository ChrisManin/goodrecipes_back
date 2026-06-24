<?php

namespace Drupal\gr_rest\Finder;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\gr_rest\Service\SeasonResolver;

class HomePageFinder {

  // French day names keyed by PHP date('N') value (ISO day: 1=Monday).
  private const DAY_NAMES = [
    1 => 'lundi', 2 => 'mardi', 3 => 'mercredi', 4 => 'jeudi',
    5 => 'vendredi', 6 => 'samedi', 7 => 'dimanche',
  ];

  // Minimal recipe fields for homepage cards.
  private const RECIPE_CARD_FIELDS = ['id', 'type', 'title', 'alias', 'visual', 'created', 'description'];

  public function __construct(
    protected EntityTypeManagerInterface $entityTypeManager,
    protected SeasonResolver $seasonResolver
  ) {}

  /**
   * Block 1 — 4 newest seasonal recipes with a photo.
   */
  public function getSuggestions(): array {
    $seasonId = $this->seasonResolver->getCurrentSeasonId();
    if (!$seasonId) {
      return [];
    }

    $nodeStorage = $this->entityTypeManager->getStorage('node');
    $query = $nodeStorage->getQuery()
      ->condition('type', 'recipe')
      ->condition('status', 1)
      ->condition('field_season', $seasonId)
      ->exists('field_recipe_visual')
      ->sort('created', 'DESC')
      ->range(0, 4)
      ->accessCheck(FALSE);

    $nids = $query->execute();
    return $this->loadAndFormat($nids, self::RECIPE_CARD_FIELDS);
  }

  /**
   * Block 2 — 8 random seasonal recipes, excluding given IDs.
   *
   * Selection is seeded by today's date so results are stable for 24 hours.
   */
  public function getSeasonalRecipes(array $excludeIds): array {
    $seasonId = $this->seasonResolver->getCurrentSeasonId();
    if (!$seasonId) {
      return [];
    }

    $nodeStorage = $this->entityTypeManager->getStorage('node');
    $query = $nodeStorage->getQuery()
      ->condition('type', 'recipe')
      ->condition('status', 1)
      ->condition('field_season', $seasonId)
      ->accessCheck(FALSE);

    if (!empty($excludeIds)) {
      $query->condition('nid', $excludeIds, 'NOT IN');
    }

    $nids = array_values($query->execute());

    srand((int) date('Ymd'));
    shuffle($nids);
    $selected = array_slice($nids, 0, 8);

    $entities = $nodeStorage->loadMultiple($selected);
    usort($entities, fn($a, $b) => $b->getCreatedTime() - $a->getCreatedTime());

    return array_map(fn($r) => $r->getRest(self::RECIPE_CARD_FIELDS), $entities);
  }

  /**
   * Block 3 — All categories.
   */
  public function getCategories(): array {
    $termStorage = $this->entityTypeManager->getStorage('taxonomy_term');
    $tids = $termStorage->getQuery()
      ->condition('vid', 'category')
      ->accessCheck(FALSE)
      ->execute();

    $terms = $termStorage->loadMultiple($tids);
    return array_map(fn($t) => $t->getRest(), $terms);
  }

  /**
   * Block 4 — 4 random seasonal thermomix recipes.
   *
   * Uses seed + 1 so the selection differs from block 2 on the same day.
   */
  public function getThermomixRecipes(): array {
    $seasonId = $this->seasonResolver->getCurrentSeasonId();
    if (!$seasonId) {
      return [];
    }

    $nodeStorage = $this->entityTypeManager->getStorage('node');
    $nids = array_values($nodeStorage->getQuery()
      ->condition('type', 'recipe')
      ->condition('status', 1)
      ->condition('field_season', $seasonId)
      ->condition('field_recipe_thermomix', 1)
      ->accessCheck(FALSE)
      ->execute());

    srand((int) date('Ymd') + 1);
    shuffle($nids);
    $selected = array_slice($nids, 0, 4);

    $entities = $nodeStorage->loadMultiple($selected);
    usort($entities, fn($a, $b) => $b->getCreatedTime() - $a->getCreatedTime());

    return array_map(fn($r) => $r->getRest(self::RECIPE_CARD_FIELDS), $entities);
  }

  /**
   * Block 5 — Current week's meal plan, keyed by day then meal type.
   *
   * Returns e.g. ['lundi' => ['date' => '2026-06-09', 'dejeuner' => {...}], ...]
   */
  public function getWeeklyMenu(): array {
    $today = date('Y-m-d');
    $nodeStorage = $this->entityTypeManager->getStorage('node');

    $planIds = $nodeStorage->getQuery()
      ->condition('type', 'nutrition_plan')
      ->condition('status', 1)
      ->condition('field_nutrition_plan_start_date', $today, '<=')
      ->condition('field_nutrition_plan_end_date', $today, '>=')
      ->range(0, 1)
      ->accessCheck(FALSE)
      ->execute();

    if (empty($planIds)) {
      return [];
    }

    $plan = $nodeStorage->load(reset($planIds));
    $result = [];

    foreach ($plan->getMeals() as $meal) {
      $date = $meal['date'] ?? NULL;
      $type = $meal['meal_type'] ?? NULL;
      if (!$date || !$type) {
        continue;
      }

      $dayName = self::DAY_NAMES[(int) date('N', strtotime($date))] ?? NULL;
      if (!$dayName) {
        continue;
      }

      $result[$dayName]['date'] = $date;
      $result[$dayName][$type] = $meal;
    }

    return $result;
  }

  private function loadAndFormat(array $nids, array $fields): array {
    if (empty($nids)) {
      return [];
    }
    $entities = $this->entityTypeManager->getStorage('node')->loadMultiple($nids);
    // Preserve sort order from the query.
    $sorted = [];
    foreach ($nids as $nid) {
      if (isset($entities[$nid])) {
        $sorted[] = $entities[$nid]->getRest($fields);
      }
    }
    return $sorted;
  }

}
