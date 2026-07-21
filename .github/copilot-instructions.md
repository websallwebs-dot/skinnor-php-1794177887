# Copilot instructions for Skinnor-PHP

Purpose: short, machine-friendly guidance for Copilot-style coding sessions in this repository. Use these instructions to find build/test/lint commands, important architecture notes, and project-specific conventions.

---

## Quick commands (repo root = skinnor/)

- Install PHP deps: `composer install`
- Install JS deps: `npm install`
- Full setup (creates .env, key, runs migrations, builds assets): `composer run setup`
- Development (server + queue + vite): `composer run dev`
- Run dev server only: `php artisan serve`
- Build frontend: `npm run build`

**Testing**
- Run full test suite (Pest/Laravel): `composer test`
- Run tests directly with Pest: `./vendor/bin/pest` (Windows: `vendor\bin\pest.bat`)
- Run a single test file: `./vendor/bin/pest tests/Feature/SomeTest.php`
- Run a single test class/method: `php artisan test --filter "ClassName::methodName"`

**Lint / style**
- Pint (code style): `./vendor/bin/pint` (Windows: `vendor\bin\pint.bat`)

**Database / migrations**
- Local env example: `skinnor/.env.example`
- Central migrations: `php artisan migrate`
- Tenant migrations (stancl tenancy): `php artisan tenants:migrate`
- Verify migrations: `php artisan migrate --pretend`

**Cache/optimization**
- Clear caches after config changes: `php artisan optimize:clear`

**Note:** phpunit.xml sets testing DB to sqlite :memory: — all tests run against in-memory database.

---

## High-level architecture (big picture)

**Stack:**
- Laravel 13.8 + PHP 8.3
- Filament v5 (not v3/v4) with 4 panels: Superadmin, Admin, Profesional, Paciente
- Multi-database tenancy via stancl/tenancy v3.10 (database-based, not domain-based)
- Spatie Media Library for file storage (not standard FileUpload)
- MySQL local (XAMPP/Herd), with separate databases per tenant
- Frontend: Vite + Tailwind CSS v4

**Key directories:**
- `app/Filament/{Superadmin,Admin,Profesional,Paciente}/` — Panel-specific Resources, Pages, RelationManagers
- `app/TenancyDatabaseManagers/` — Custom tenant DB managers
- `database/migrations/` — Central migrations (tenants, superadmin/admin users)
- `database/migrations/tenant/` — Tenant-specific migrations (clinics, professionals, patients, lesions, etc.)
- `config/tenancy.php` — Tenancy configuration (database-based)
- `tests/` — Pest test suite
- `resources/` — Vite + Tailwind assets

**Important:** Each clinic gets its own MySQL database named `tenant_<CLINIC_ID>`. Stancl automatically runs tenant migrations when a new Clinica is created.

---

## Critical Filament v5 conventions

**⚠️ MOST IMPORTANT:** This project uses **Filament v5**, not v3 or v4. Namespaces and imports changed significantly.

### Correct Filament v5 imports
```php
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

public static function form(Schema $form): Schema { ... }  // NOT Form
```

### NEVER use v3/v4 imports (these will fail)
```php
// ❌ WRONG
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\EditAction;
```

### Navigation and labels — ALWAYS use translation keys
```php
// ✅ CORRECT
public static function getNavigationLabel(): string { return __('nav.pacientes'); }
public static function getModelLabel(): string { return __('nav.pacientes'); }
public static function getPluralModelLabel(): string { return __('nav.pacientes'); }

// ❌ NEVER hardcode
protected static ?string $navigationLabel = 'Pacientes';
```

### File uploads — MUST use Spatie Media Library, NOT standard FileUpload
```php
// ✅ For single images (profile photos)
use Filament\SpatieLaravelMediaLibraryPlugin\Forms\Components\SpatieMediaLibraryFileUpload;

SpatieMediaLibraryFileUpload::make('foto')
    ->collection('foto')
    ->image()
    ->imageEditor()
    ->columnSpanFull()

// ✅ For multiple images (clinical photos)
SpatieMediaLibraryFileUpload::make('fotos_clinicas')
    ->collection('fotos_clinicas')
    ->image()
    ->multiple()
    ->maxSize(10240)

// ❌ NEVER use standard FileUpload for files
FileUpload::make('foto')->disk('public')->directory('fotos')
```

### Models with media must implement HasMedia
```php
use Spatie\MediaLibrary\Interaction\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Profesional extends Model implements HasMedia
{
    use InteractsWithMedia;
    
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('foto')->singleFile();
    }
}
```

### Database — MySQL enum syntax (not strings)
```php
$table->enum('role', ['admin', 'profesional', 'paciente']);
```

### Relationship selects — use `relationship()` helper
```php
Select::make('author_id')
    ->relationship(name: 'author', titleAttribute: 'name')
    ->searchable()  // For large datasets
    ->preload()     // For small datasets
```

### CRITICAL: Never mix central and tenant migrations
- Central migrations: `database/migrations/` — ONLY tenants table, domain configs, superadmin/admin users
- Tenant migrations: `database/migrations/tenant/` — Clinic data (professionals, patients, lesions, consultations)
- **NEVER move files between these folders.**

### Always use full descriptive names — NO abbreviations
```php
// ✅ CORRECT
$exception, $component, $response, $configuration, $record, $livewire

// ❌ WRONG
$e, $comp, $resp, $config, $rec, $lw
```

---

## Tenancy rules (stancl/tenancy v3, database-based)

- Each clinic has its own database: `tenant_<CLINIC_ID>`
- Central database has: tenants, domains (if configured), superadmin/admin users
- Tenant database auto-created when Clinica model is created (via Stancl observers)
- Tenant migrations auto-run on new database creation
- **Do NOT use InitializeTenancyByDomain** unless domains are properly configured (see config/tenancy.php)
- Central tables should NOT contain clinical data (professionals, patients, lesions, etc.)

---

## Testing requirements

- Tests use in-memory SQLite (phpunit.xml)
- When writing tests that need tenant data:
  - Use tenant factories if available in repo
  - Or manually create tenant and run its migrations within test
  - Run `composer test` to validate all tests locally
- Validate UI accessibility in both light/dark modes when testing Filament forms

---

## Authoritative documentation

When in doubt, consult (in order of priority):
1. `skinnor/.windsurf/PROJECT_RULES.md` — Project-specific rules (Filament v5, Tenancy, Migrations)
2. `standards/FILAMENT_4x_STANDARDS.md` — Filament naming conventions (despite "4x", applies to v5)
3. `GEMINI.md` — Filament best practices guide
4. `skinnor/docs/` — Architecture and localization docs
5. `skinnor/app/Filament/*` — Working examples of Resources, Pages, RelationManagers

---

## When making changes, Copilot should

- Always use `php artisan migrate --pretend` before running migrations
- Run `php artisan optimize:clear` after config/provider changes
- After code changes, run `composer test` to validate (or single test with --filter)
- Place new Filament resources under correct panel: `app/Filament/{PanelName}/Resources/`
- Use translation keys (`__()`) for ALL labels, placeholders, headers
- Verify imports use Filament v5 namespaces (Schemas, not Forms; Actions, not Tables\Actions)

---

Co-authored-by: Copilot <223556219+Copilot@users.noreply.github.com>
