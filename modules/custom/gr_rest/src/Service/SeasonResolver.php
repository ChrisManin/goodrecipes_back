<?php

namespace Drupal\gr_rest\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;

class SeasonResolver {

  // Maps month number to French season term name.
  // Term names in the 'season' vocabulary MUST match exactly.
  private const SEASON_BY_MONTH = [
    1 => 'Hiver', 2 => 'Hiver',
    3 => 'Printemps', 4 => 'Printemps', 5 => 'Printemps',
    6 => 'Été', 7 => 'Été', 8 => 'Été',
    9 => 'Automne', 10 => 'Automne', 11 => 'Automne',
    12 => 'Hiver',
  ];

  public function __construct(
    protected EntityTypeManagerInterface $entityTypeManager
  ) {}

  public function getCurrentSeasonId(): ?int {
    $month = (int) date('n');
    $seasonName = self::SEASON_BY_MONTH[$month] ?? NULL;
    if (!$seasonName) {
      return NULL;
    }

    $termStorage = $this->entityTypeManager->getStorage('taxonomy_term');
    $tids = $termStorage->getQuery()
      ->condition('vid', 'season')
      ->condition('name', $seasonName)
      ->accessCheck(FALSE)
      ->execute();

    return !empty($tids) ? (int) reset($tids) : NULL;
  }

}
