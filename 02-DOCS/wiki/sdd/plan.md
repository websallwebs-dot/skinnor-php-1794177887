# Plan Técnico — Accesos, Landing y Logins (Skinnor-PHP)

## Stack
- Laravel 13.11.2 + PHP 8.3
- Filament v5.6 (paneles existentes, sin cambios)
- stancl/tenancy v3 (middleware de tenencia, configurado para SQLite en desarrollo local)
- Tailwind CSS (vistas Blade, sin cambios de build)

## Archivos modificados

### 1. `resources/views/landing.blade.php`
- Reescrita como landing informativa (modo Persuade).
- Header: solo dos botones cuando no autenticado:
  - "Iniciar sesión" → `/login`
  - "Crear cuenta" → `/register`
- Sin selector de roles. Sin enlaces a admin/superadmin.
- Hero, sección "Cómo funciona", sección "Quién puede usar" (paciente/profesional con enlaces a `/login`).
- Cuando autenticado: perfil, nombre, botón "Salir".

### 2. `resources/views/auth/login.blade.php`
- Formulario simple: solo email y contraseña.
- Quitado selector de roles (paciente/profesional/admin).
- Quitado campo oculto `role` y JavaScript asociado.
- Enlace "Crear cuenta" → `/register`.

### 3. `config/tenancy.php`
- `central_connection` y `template_tenant_connection` cambiados de `mysql` a `sqlite`.
- `managers.sqlite` usando `App\TenancyDatabaseManagers\SqliteDatabaseManager`.

### 4. `app/TenancyDatabaseManagers/SqliteDatabaseManager.php`
- Corregido `databaseExists(string $name)` — firma compatible con la interfaz de Stancl Tenancy v3.
- Corregido `makeConnectionConfig(array $baseConfig, string $databaseName)` — usa `$databaseName` directamente sin agregar prefix `tenant_`.

## Datos creados en base de datos

### Tenant (DB central SQLite)
- `clinica-local` con dominios `127.0.0.1` y `localhost`

### Migraciones de tenant (DB tenant SQLite)
- profesionales, pacientes, lesiones, seguimientos, consultas, imagenes_seguimiento, media

### Usuarios (DB central)
| Email | Rol | Contraseña |
|-------|-----|------------|
| admin@skinnor.com | superadmin | password |
| admin@skinnor.cl | admin | password |
| prof@skinnor.cl | profesional | password |
| paciente@skinnor.cl | paciente | password |

## Flujo de navegación

```
Landing (/?) — sin autenticar:
├─► Iniciar sesión  → /login  → [email+password] → /paciente | /profesional | /admin (según rol)
├─► Crear cuenta    → /register
└─► (sin enlaces a Admin ni Superadmin)

Acceso directo (URL conocida):
/superadmin/login → /superadmin (solo rol superadmin)
```

## Validación y pruebas

1. `php artisan test` — 1 fallo preexistente (tabla `domains` en SQLite in-memory de testing), no relacionado con estos cambios.
2. `./vendor/bin/pint` — problemas de formato preexistentes en archivos no modificados.
3. Validación manual con curl: `/` → 200, `/login` → 200, `/register` → 200, `/superadmin/login` → 200.

## Orden de implementación (completado)

1. ✅ Modificar `landing.blade.php` (landing informativa, solo 2 botones)
2. ✅ Modificar `login.blade.php` (login simple, sin selector de roles)
3. ✅ Corregir tenancy para localhost (`config/tenancy.php`, `SqliteDatabaseManager`)
4. ✅ Crear tenant y dominios para `127.0.0.1` y `localhost`
5. ✅ Ejecutar `php artisan tenants:migrate`
6. ✅ Crear usuarios de prueba en DB central
