<?php

/**
 * Migration : field_recipe_preparation (text_long) → field_recipe_steps (paragraphs).
 *
 * Pour chaque recette ayant une préparation textuelle et pas encore d'étapes,
 * crée un step_paragraph avec le contenu existant et l'attache à field_recipe_steps.
 *
 * Usage : vendor/bin/drush php:script scripts/migrate_preparation_to_steps.php
 *
 * Le script est idempotent : relancé une 2e fois, il ne retraite pas
 * les recettes qui ont déjà des étapes.
 */

use Drupal\paragraphs\Entity\Paragraph;

$nodeStorage = \Drupal::entityTypeManager()->getStorage('node');

// Charge toutes les recettes publiées ou non.
$nids = $nodeStorage->getQuery()
  ->condition('type', 'recipe')
  ->accessCheck(FALSE)
  ->execute();

if (empty($nids)) {
  echo "Aucune recette trouvée.\n";
  return;
}

$migrated = 0;
$skipped  = 0;
$empty    = 0;

foreach ($nids as $nid) {
  /** @var \Drupal\node\NodeInterface $recipe */
  $recipe = $nodeStorage->load($nid);
  $title  = $recipe->getTitle();

  // Déjà migré : on passe.
  if (!$recipe->get('field_recipe_steps')->isEmpty()) {
    echo "  [skip] #$nid « $title » — steps déjà présents.\n";
    $skipped++;
    continue;
  }

  $preparationField = $recipe->get('field_recipe_preparation');

  // Pas de texte de préparation : rien à migrer.
  if ($preparationField->isEmpty()) {
    echo "  [empty] #$nid « $title » — pas de préparation.\n";
    $empty++;
    continue;
  }

  $text   = $preparationField->value;
  $format = $preparationField->format ?? 'fully_html';

  // Crée le paragraph step_paragraph avec le texte existant.
  $step = Paragraph::create([
    'type' => 'step_paragraph',
    'field_step_paragraph_description' => [
      'value'  => $text,
      'format' => $format,
    ],
  ]);
  $step->save();

  // Attache le paragraph à la recette.
  $recipe->set('field_recipe_steps', [
    [
      'target_id'          => $step->id(),
      'target_revision_id' => $step->getRevisionId(),
    ],
  ]);
  $recipe->save();

  echo "  [ok] #$nid « $title » → step_paragraph #{$step->id()} créé.\n";
  $migrated++;
}

echo "\n--- Résumé ---\n";
echo "Migrées  : $migrated\n";
echo "Skippées : $skipped (déjà des steps)\n";
echo "Vides    : $empty (pas de préparation)\n";
echo "Total    : " . ($migrated + $skipped + $empty) . " recettes traitées.\n";
