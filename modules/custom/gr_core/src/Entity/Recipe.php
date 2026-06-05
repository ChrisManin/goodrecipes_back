<?php

namespace Drupal\gr_core\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\gr_core\Entity\AbstractNode;
use Drupal\Core\Datetime\DrupalDateTime;

class Recipe extends AbstractNode
{

	/**
	 * {@inheritdoc}
	 */
	public static function postDelete(EntityStorageInterface $storage, array $entities)
	{

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
		'dish_type',
		'thermomix',
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
					$data = $this->bundle();
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
					$data = $this->getCategory()?->getRest();
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
				case 'thermomix':
					$data = $this->getThermomix();
					break;
			}
			if ($data) {
				$rest[$field] = $data;
			}
		}
		return $rest;
	}

	public function getRecipeVisualFile(string $field_name = 'field_recipe_visual'): ?File
	{
		return parent::getVisualFile($field_name);
	}

	public function getCreatedDate(): ?string
	{
		$created = DrupalDateTime::createFromTimestamp($this->getCreatedTime());
		return $created->format('j F Y', ['langcode' => 'fr']);
	}

	public function getRestVisual(string $field_name = 'field_recipe_visual'): array
	{
		return parent::getRestVisual($field_name);
	}

	// Overrides AbstractNode::getCategory() which uses the wrong field name 'field_category'
	public function getCategory(): ?Category
	{
		return $this->field_recipe_category?->first()?->get('entity')?->getTarget()?->getValue();
	}

	public function getCaloricIntake(): ?int
	{
		if (!$this->get('field_recipe_caloric_intake')->isEmpty()) {
			return (int) $this->get('field_recipe_caloric_intake')->value;
		}
		return null;
	}

	public function getCookingDuration(): ?int
	{
		if (!$this->get('field_recipe_cooking_duration')->isEmpty()) {
			return (int) $this->get('field_recipe_cooking_duration')->value;
		}
		return null;
	}

	public function getPrepareDuration(): ?int
	{
		if (!$this->get('field_recipe_prepare_duration')->isEmpty()) {
			return (int) $this->get('field_recipe_prepare_duration')->value;
		}
		return null;
	}

	public function getDescription(): ?string
	{
		if (!$this->get('field_recipe_description')->isEmpty()) {
			return $this->get('field_recipe_description')->value;
		}
		return null;
	}

	public function getDifficulty(): ?array
	{
		$difficulty = $this->field_recipe_difficulty?->first()?->get('entity')?->getTarget()?->getValue();
		return $difficulty?->getRest();
	}

	public function getIngredients(): ?array
	{
		$termStorage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
		$nodeStorage = \Drupal::entityTypeManager()->getStorage('node');
		$items = [];
		foreach ($this->get('field_recipe_ingredients') as $item) {
			$ingredient = $nodeStorage->load($item->ingredient_id);
			if ($ingredient) {
				$measurement = $item->unit ? $termStorage->load($item->unit) : null;
				$items[] = [
					'ingredient' => $ingredient->getRest(),
					'quantity' => $item->quantity,
					'unit' => $measurement?->getRest(),
				];
			}
		}
		return $items ?: null;
	}

	public function getNotes(): ?string
	{
		if (!$this->get('field_recipe_notes')->isEmpty()) {
			return $this->get('field_recipe_notes')->value;
		}
		return null;
	}

	public function getPreparation(): ?string
	{
		if (!$this->get('field_recipe_preparation')->isEmpty()) {
			return $this->get('field_recipe_preparation')->value;
		}
		return null;
	}

	public function getServings(): ?int
	{
		if (!$this->get('field_recipe_servings')->isEmpty()) {
			return (int) $this->get('field_recipe_servings')->value;
		}
		return null;
	}

	public function getSource(): ?string
	{
		if (!$this->get('field_recipe_source')->isEmpty()) {
			return $this->get('field_recipe_source')->value;
		}
		return null;
	}

	public function getTags(): ?array
	{
		$tags = [];
		foreach ($this->get('field_recipe_tags') as $item) {
			$tag = $item->get('entity')?->getTarget()?->getValue();
			if ($tag) {
				$tags[] = $tag->getRest();
			}
		}
		return $tags ?: null;
	}

	public function getDishType(): ?array
	{
		$dish_type = $this->field_recipe_type?->first()?->get('entity')?->getTarget()?->getValue();
		return $dish_type?->getRest();
	}

	public function getThermomix(): bool
	{
		return (bool) $this->get('field_recipe_thermomix')->value;
	}
}
