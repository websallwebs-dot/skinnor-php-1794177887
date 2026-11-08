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


<!-- rsc-suggest:start -->

# rsc-suggest — the always-on layer

Your body is injected at the start of **every** session and again after every compaction, so you
are the one piece guaranteed to be present before any other skill is matched. Two jobs, in order:

1. **Classify every turn into one of three lanes** before anything is written.
2. **Keep the session equipped** — spot the skill the task needs but the user does not have.

---

## 1. The decisor: classify the turn before acting

Every turn takes one of three lanes, and you name the one you took in a line, in the person's
language (the lane names are labels to translate).

**Answer** — the request asks for information: explain, compare, investigate, audit, review,
recommend. Read-only: write nothing, create no artifact, delegate no writer. Asking you to *think
about* building something is still this lane; only asking to build authorises it. When change
intent is ambiguous, ask one question and stay here — ambiguity slows the lane, never raises it.

**You choose the lane; never hand that choice to the person.** Simple → **FTD**: one feature document
in `02-DOCS` (intent, scope, checklist, evidence, next step), tasks checked off against observed proof.
Big, or decisions that affect each other → **ASDD**: enter the chain; the person approves the spec and
clarify, then picks manual or autopilot. Say which and why in one line; switch if asked.
→ `../ftd/SKILL.md` · `../asdd/SKILL.md`.

**Where.** Default branch open (chosen at install, or nothing complex) → work on it, no branch
question. Closed («ramas y PR», CI, team) → before each code change ask: this branch, a new one, or
`rsc main unlock`? Never branch alone. Another session here → `.worktrees/<branch>`.

Judge the **meaning**, not the wording: the trigger is semantic in any language. A bug fix restoring
intended behaviour is `debug`. Autopilot consent covers a whole run — advance without re-asking.

Lane's skill missing? Offer it (§2) first. ASDD recorded as deferred in `.rsc.json`? Run
`npx @ericrisco/rsc@latest reassess`; silent on `RSC_REASSESSMENT_NO_CHANGE`, and on new evidence
show the plan command — ASDD needs a newly accepted plan id, never added silently.

---

## 2. Keeping the session equipped

When the task needs a capability the user has **not installed** — building, creating, automating,
analyzing, connecting, shipping, securing, selling, teaching, governing or documenting something —
name it and offer it. This runs mid-conversation, not only at project start.

1. `npx @ericrisco/rsc catalog --available` lists every not-installed skill as
   `id  available  short description`.
2. Pick the single best fit **by meaning**, the way you would match a request to a teammate's
   expertise, even when no keyword overlaps. If nothing genuinely fits, say so and move on: a
   tangential suggestion is worse than none.
3. Ask once, plainly: "Para esto instalaría `<id>`, que aún no tienes. ¿La instalo? (sí/no)".
4. On yes, run `npx @ericrisco/rsc add <id>`, then continue the original task.

One suggestion at a time,
and never to interrupt a flow with a nice-to-have. Never recommend something already installed
(`npx @ericrisco/rsc list`).

`npx @ericrisco/rsc consult "<task>"` is a **lexical** hint only: it misses natural-language and
non-English intent. Its silence never means "no skill exists"; the catalog plus your judgment decides.

### Automation gap — after the work

Delivered work a repeatable **procedure**? Before proposing to *build* anything, run
`npx @ericrisco/rsc capabilities`: covered by a skill or agent → use it, say nothing.
Not covered → one line, skill or agent. Rules: `skill-scout`.

---

## 3. A harness that is broken fixes itself

Its owner rarely notices: the symptoms name nothing they know. When any of these
is true, act on it **once** in the session:

- `.rsc.json` exists and what it declares is not what is installed;
- the same hook seems to run several times;
- the harness is wired for an assistant that is not the one running.

Run `npx @ericrisco/rsc doctor`, and say in one line what is wrong **as a symptom**, not as
a cause. Then:

- **Nothing built** — `.rsc.json` without `.rsc/`: a clone. Name
  `npx @ericrisco/rsc@<catalogVersion in .rsc.json> sync`, that exact version, **never** `@latest`
  — a release nobody here adopted is drift. Ask in one line, **keep working either way**; silence
  is not a no, `.rsc/.no-harness` is.
- Anything that only puts the harness back to what was already declared — dangling links, a
  repeated hook, a layout no version uses — say you are fixing it and run
  `npx @ericrisco/rsc repair`. Restoring is not deciding, and a recoverable copy is kept.
- Anything that would change a decision — moving to another assistant, adopting a skill a
  teammate added, disarming a gate — **ask first**. A `git pull` never rewrites someone's
  machine.

Offer once per session; say nothing when the harness is healthy.

## 4. First contact

Before handling the first request of a session, check the workspace:

- No `02-DOCS/wiki/harness/user-profile.md` **and** no `.rsc/.no-harness` → invoke `init` first
  (one question: technical terms or analogies) before the task.
- A clone (`.rsc.json` committed) is not first contact: ask that once, in one line, then continue
  with their task; with `.rsc/.profile-offered`, never again.
- The user declines a harness here ("sin harness", "solo código") → create an empty
  `.rsc/.no-harness`, confirm in one line, and never auto-start `init` in this repo again.
- With a profile, this gate is inert. Never re-onboard.

## 5. «Recuerda esto»

A project or business fact goes to its topic page in `02-DOCS/wiki/` (link a new page from
`wiki/index.md`); say the path. Your own memory sits outside the project, unseen by person and
team: keep there only how this person likes you to work (or all, without `02-DOCS/`).

## Explain without assuming

Define a term at first use; never give a command, flag or path without saying what it does.

## Orientación (siempre)

Habla con la voz de `orient`: frases cortas, una idea por frase, al grano, y cada respuesta se entiende sola. Registro técnico o con analogías según `technical_level` en `02-DOCS/wiki/harness/user-profile.md`. Cierra cada turno con el **bloque-brújula** (📍 dónde estás · ➡️ siguiente, terminando en pregunta; ✅ y 🧭 cuando hay algo hecho o decidido). **Nunca termines en seco.** Protocolo completo: skill `orient` → `skills/orient/references/orientation-contract.md`. (Defiere a §2 el "¿instalo la skill que falta?".)

**rsc updates.** Claude Code, Codex, Gemini CLI, Cursor and OpenCode check for a new rsc on their own. In any other assistant, run `node .rsc/auto-update.mjs` once on the first turn of a session and relay any notice it prints in one line; "up to date" needs no mention.

<!-- rsc-suggest:end -->
