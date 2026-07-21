# Skinnor-PHP — Agentic Guide

Laravel 13.8 + PHP 8.3 project under `skinnor/`. Multi-tenant dermatology SaaS with **Filament v5.6** (NOT v3/v4).

## Repo layout

- `skinnor/` — Laravel root (all commands run here)
- `standards/`, `.github/copilot-instructions.md`, `GEMINI.md` — reference docs
- Root `AGENTS.md` (this file) is the primary instruction source.

## Essential commands (run inside `skinnor/`)

```bash
composer run setup        # Full first-time setup (.env, key, migrate, npm build)
composer run dev          # Concurrent: `php artisan serve` + queue:listen + npm run dev
composer test             # config:clear + pest test suite

# Focused testing
./vendor/bin/pest tests/Feature/SomeTest.php
php artisan test --filter "ClassName::methodName"

# Lint (Pint)
./vendor/bin/pint

# Migrations
php artisan migrate                           # central
php artisan tenants:migrate                   # all tenants
php artisan migrate --pretend                 # preview before running
php artisan migrate:status

# Cache
php artisan optimize:clear                    # after config/provider changes
# Run this after every config, route, or provider change.

# Artisan serve
php artisan serve --port=8080
php artisan tinker
php artisan storage:link                      # local media disk setup
```

## Filament v5 — critical differences from v3/v4

```php
// ✅ CORRECT (v5)
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

public static function form(Schema $form): Schema { ... }

// ❌ WRONG (v3/v4 — will crash at runtime)
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\EditAction;
```

## NEVER hardcode UI strings — always use translation keys

```php
// ✅
public static function getNavigationLabel(): string { return __('nav.pacientes'); }
->label(__('fields.nombre'))

// ❌
protected static ?string $navigationLabel = 'Pacientes';
->label('Nombre')
```

4 languages always updated together: `lang/{es,en,pt,fr}/`.

## File uploads — ALWAYS Spatie Media Library

```php
use Filament\SpatieLaravelMediaLibraryPlugin\Forms\Components\SpatieMediaLibraryFileUpload;

// Single
SpatieMediaLibraryFileUpload::make('foto')->collection('foto')->image()->imageEditor()->columnSpanFull();
// Multiple
SpatieMediaLibraryFileUpload::make('fotos_clinicas')->collection('fotos_clinicas')->image()->multiple()->maxSize(10240);
```

Models with uploads implement `Spatie\MediaLibrary\HasMedia` + `InteractsWithMedia`.

## 4 Filament panels

| Panel | URL | Role | DB |
|-------|-----|------|----|
| Superadmin | `/superadmin` | `superadmin` | central |
| Admin (`->default()`) | `/admin` | `admin` | tenant |
| Profesional | `/profesional` | `profesional` | tenant |
| Paciente | `/paciente` | `paciente` | tenant |

**Tenancy**: stancl/tenancy v3 — each clinic = its own MySQL DB `tenant_{uuid}`.  
`config/tenancy.php` and `app/Providers/TenancyServiceProvider.php` control this.

## Migration rules

- `database/migrations/` → central only (tenants, domains, central users, media table)
- `database/migrations/tenant/` → business tables (profesionales, pacientes, lesiones, seguimientos, consultas, etc.)
- **NEVER move files between them. NEVER put tenant tables in central migrations.**

## Database conventions

- Enum syntax (not strings): `$table->enum('role', ['admin', 'profesional', 'paciente']);`
- No abbreviations in variable names (`$exception` not `$e`)
- Central DB never has clinical data (only tenants, domains, superadmin/admin users)

## Testing

- Pest suite, `phpunit.xml` uses in-memory SQLite
- Tests needing tenant data: create tenant + run its migrations manually within test
- Run `composer test` before pushing

## Dev credentials (local)

- `super@skinnor.cl` / `password` → `/superadmin/login`
- `admin@skinnor.cl` / `password` → `/admin/login`
- `prof@skinnor.cl` / `password` → `/profesional`
- `paciente@skinnor.cl` / `password` → `/paciente`

## Windows vendor patches

3 vendor files patched for `tmpfile()` issues on Windows (Livewire + Spatie Media Library). Linux/prod not affected.

## Known error quick-fix table

| Error | Fix |
|-------|-----|
| `Class "Filament\Forms\Components\Section" not found` | Use `Filament\Schemas\Components\Section` |
| `Class "Filament\Tables\Actions\EditAction" not found` | Use `Filament\Actions\EditAction` |
| `form(Form $form): Form` signature mismatch | Change to `form(Schema $form): Schema` |
| `relation "pacientes" does not exist` | Run `php artisan tenants:migrate` |
| Duplicate column in migration | Migration placed in wrong folder (see migration rules) |

## Authoritative docs (in priority order)

1. `.windsurf/PROJECT_RULES.md` (most complete project rules)
2. `standards/FILAMENT_4x_STANDARDS.md` (Filament naming — despite name, applies to v5)
3. `docs/PROYECTO.md` and `docs/internal/ARCHITECTURE.md`
4. `app/Filament/*/Resources/` — working v5 examples
