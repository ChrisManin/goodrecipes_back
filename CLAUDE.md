# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

### Dependencies
```bash
composer install
```

### Drush (Drupal CLI)
```bash
vendor/bin/drush cr                         # Clear all caches
vendor/bin/drush cex                        # Export configuration to configs/ sync directory
vendor/bin/drush cim                        # Import configuration from configs/ sync directory
vendor/bin/drush updb                       # Run pending database updates
vendor/bin/drush en <module>                # Enable a module
vendor/bin/drush pm-uninstall <module>      # Uninstall a module
```

### Local development setup
1. Copy `sites/default/example.settings.local.php` → `sites/default/settings.local.php` and configure DB credentials
2. `composer install`
3. `vendor/bin/drush site-install` (fresh install) or `vendor/bin/drush cim` (import existing config)

## Architecture

Drupal 10 headless/decoupled CMS using the `legacy-project` layout — the web-root **is** the repository root (no `web/` subdirectory). The site exposes a REST API consumed by a separate frontend.

### Custom modules (`modules/custom/`)

**gr_core** — Core domain entities. Extends Drupal's built-in entity types with typed PHP classes that carry business logic:
- `AbstractNode` / `AbstractTerm` — base classes adding `getAlias()`, `getRestVisual()`, `getVisualFile()`
- `Recipe`, `Ingredient` extend `AbstractNode`
- `Category`, `Difficulty`, `Season`, `DishType` extend `AbstractTerm`
- `File` extends Drupal's core `File` entity, adds `buildImageStyleUri()` which appends `.webp` to the generated image style URI
- Bundle-to-class mapping is registered in `gr_core.module` via `hook_entity_bundle_info_alter()` so Drupal instantiates these classes automatically
- **Important:** the Recipe content type has machine name `recette` (French), not `recipe` — this affects any bundle checks in hooks (e.g. in `quantity_field.module`)

**gr_rest** — REST serialization layer. Registers Symfony Normalizer services (priority 10) for each custom entity type so the API returns shaped JSON instead of raw Drupal field arrays:
- Normalizers are thin: they simply call `$entity->getRest()`. All JSON shape logic lives in the entity class, not the normalizer.
- `CategoryConverter`, `DifficultyConverter` — param converters for route upcasting. They resolve a URL alias to an internal path via `path_alias.manager`, then load the entity. This allows REST routes to accept alias-based parameters.
- All services declared in `gr_rest.services.yml` with priority 10

**quantity_field** — Custom field plugin (FieldType + FieldWidget + FieldFormatter) for ingredient quantity/unit pairs:
- Field properties: `ingredient_id` (integer, references Ingredient node), `quantity` (integer), `unit` (string)
- Data is also denormalized into a `quantity_field_data` table for faster queries. Sync happens in `hook_entity_presave` (not presave on the field itself): on save of a `recette` node, old rows are deleted and new rows inserted from `field_recipe_ingredients`.
- Widget loads unit options dynamically from the `measurement` taxonomy vocabulary (`field_measurement_abbreviation`)

### Custom theme (`themes/custom/`)

**gr_theme** — Admin theme extending `adminimal_theme`. Used for the Drupal back-office only, not served to API consumers.

### Configuration management

Drupal configuration is exported to and imported from:
```
configs/config_<hash>/sync/
```
The hash is derived from the site's config sync path in `settings.php`. Always run `drush cex` after structural changes (content types, fields, views, REST resources) and commit the resulting YAML files.

### Key contrib modules

| Module | Purpose |
|---|---|
| `rest` + `serialization` | Core REST API foundation |
| `paragraphs` | Embedded content blocks within recipes |
| `imagemagick` | Server-side image processing |
| `search_api` | Recipe search indexing |
| `scheduler` | Publish/unpublish scheduling |
| `pathauto` | Auto-generated URL aliases |
| `pager_serializer` | Pagination metadata in REST responses |
| `redirect` | URL redirect management |

## Adding a new entity type

1. Create a PHP class in `modules/custom/gr_core/src/Entity/` extending `AbstractNode` or `AbstractTerm`
2. Implement `getRest()` on the class — this is the single source of truth for the JSON shape; the normalizer will just call it
3. Register the bundle class in `gr_core.module` via `hook_entity_bundle_info_alter()`
4. Add a Normalizer in `modules/custom/gr_rest/src/Normalizer/` (one line: call `getRest()`) and register it in `gr_rest.services.yml` with priority 10
5. If the entity has visual files, add a `postDelete()` hook to clean up the referenced file entity
6. Define fields/content-type configuration in the Drupal UI, then `drush cex` to export
