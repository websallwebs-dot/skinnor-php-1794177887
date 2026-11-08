# Tasks — Accesos, Landing y Logins (Skinnor-PHP)

## Tarea 1: Landing informativa (modo Persuade) — solo botones de acceso
- [x] 1.1 Header: solo dos botones cuando no autenticado → "Iniciar sesión" (`/login`) y "Crear cuenta" (`/register`)
- [x] 1.2 Sin selector de roles (paciente/profesional/admin) en la landing
- [x] 1.3 Sin enlaces a `/admin` ni `/superadmin/login` desde landing pública
- [x] 1.4 Contenido informativo: hero, cómo funciona, quién puede usar (sin datos sensibles de clínicas ni directorio)
- [x] 1.5 Cuando autenticado: mostrar perfil, salir (sin tarjetas de superadmin/clínicas)

## Tarea 2: Login simple (sin selector de roles)
- [x] 2.1 Quitar selector de roles (paciente/profesional/admin) del formulario
- [x] 2.2 Quitar campo oculto `role`
- [x] 2.3 Solo email + password + botón "Iniciar sesión"
- [x] 2.4 Enlace "Crear cuenta" → `/register`
- [x] 2.5 Sin JavaScript de selector de roles

## Tarea 3: LoginController (sin cambios necesarios)
- [x] 3.1 `login()` ya redirige por rol del usuario autenticado (admin → /admin, profesional → /profesional, paciente → /paciente)
- [x] 3.2 `loginSuperadmin()` ya restringe a rol `superadmin`

## Tarea 4: Arreglar tenancy para localhost (CRÍTICO - preexistente)
- [x] 4.1 Crear tenant `clinica-local` en tabla `tenants` (DB central SQLite)
- [x] 4.2 Crear dominios `127.0.0.1` y `localhost` en tabla `domains`
- [x] 4.3 Corregir `config/tenancy.php` para usar SQLite (central_connection y template_tenant_connection)
- [x] 4.4 Corregir `SqliteDatabaseManager::databaseExists(string $name)` y `makeConnectionConfig(array, string)` firma de métodos
- [x] 4.5 Ejecutar `php artisan tenants:migrate` para crear tablas de negocio en tenant
- [x] 4.6 Crear usuarios de prueba (superadmin, admin, profesional, paciente) en DB central

## Tarea 5: Validación final
- [x] 5.1 `http://127.0.0.1:8000/` → 200 OK (landing informativa)
- [x] 5.2 `http://127.0.0.1:8000/login` → 200 OK (login simple)
- [x] 5.3 `http://127.0.0.1:8000/register` → 200 OK (registro)
- [x] 5.4 `http://127.0.0.1:8000/superadmin/login` → 200 OK (superadmin)
- [ ] 5.5 `php artisan test` — 1 fallo preexistente (tabla `domains` en SQLite in-memory), no relacionado con estos cambios
- [ ] 5.6 `./vendor/bin/pint` — problemas de formato preexistentes en archivos no modificados
