# Filament 4.x Agent Instructions
Source: https://raw.githubusercontent.com/filamentphp/filament/4.x/CLAUDE.md

## Critical: Naming Conventions
*   **Never use abbreviated variable names.** Use full descriptive names: `$exception`, `$component`, `$response`, `$configuration`, `$record`, `$livewire`.
*   **Pest Test Names:** Always use backticks for code references. Add `()` for methods: `it('can use `aspectRatio()` to force image cropping')`.
*   **Code Comments:** Use backticks when referencing code: `// Uses `evaluate()` to resolve the `Closure``.

## Development Commands
*   Update tests when making changes.
*   Validate UI with `assertNoAccessibilityIssues()` in both light and dark modes.

## Coding Patterns
*   **Fluent API:** Use `make()` constructor and chainable methods.
*   **Boolean Methods:** Use `is/should/can/has` prefix for properties, verb form for setters.
*   **Static Closures:** Use `static fn` when `$this` is not needed.
*   **Container Resolution:** Use `app()` instead of `new`.
*   **No Final/Readonly:** Allow extensibility for Filament classes.

## CSS Hooks
*   **Never use Tailwind classes directly in Blade views.** Use `@apply` in CSS files with `fi-` prefix.
