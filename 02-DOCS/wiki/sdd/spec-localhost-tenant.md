# Spec — Tenant por defecto para localhost (Skinnor-PHP)

## Problema
El middleware `InitializeTenancyByDomain` de Stancl Tenancy falla en `127.0.0.1` porque no hay ningún tenant asociado a ese dominio en la tabla `domains` de la base de datos central. Esto impide acceder a `/login`, `/register`, `/admin`, `/profesional`, `/paciente` desde localhost.

## Objetivo
Crear un tenant por defecto para `127.0.0.1` y `localhost` en la base de datos central, con sus migraciones de tenant ejecutadas y usuarios de prueba creados, para que el desarrollo local funcione sin errores de tenancy.

## Alcance
1. Crear tenant `clinica-local` en tabla `tenants` (DB central)
2. Crear dominios `127.0.0.1` y `localhost` asociados a ese tenant
3. Ejecutar `php artisan tenants:migrate` para crear tablas de negocio en el tenant
4. Ejecutar seeder de usuarios de prueba para ese tenant
5. Verificar que `/login`, `/register`, `/admin`, `/profesional`, `/paciente` funcionen en `http://127.0.0.1:8000`

## Fuera de alcance
- Cambios en código de la aplicación (vistas, controladores, rutas)
- Configuración de producción (dominios reales de clínicas)

## Criterios de aceptación
1. `http://127.0.0.1:8000/login` retorna 200 (no 500)
2. `http://127.0.0.1:8000/register` retorna 200
3. `http://127.0.0.1:8000/admin` redirige a login o muestra panel (no error de tenancy)
4. `http://127.0.0.1:8000/profesional` redirige a login o muestra panel (no error de tenancy)
5. `http://127.0.0.1:8000/paciente` redirige a login o muestra panel (no error de tenancy)
6. Credenciales de prueba funcionan: `paciente@skinnor.cl` / `password`, `prof@skinnor.cl` / `password`
