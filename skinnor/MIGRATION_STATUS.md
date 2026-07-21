# Estado Actual del Proyecto (Snapshot)

## Fecha
2 de junio de 2026

## Estado General
- **Panel Administrativo**: Funcional y multilingüe.
- **Paneles Profesional/Paciente**: Funcional y multilingüe.
- **Sistema de Traducción**: Implementado via `filament-language-switch` y claves de traducción (`__('key')`).
- **Migración a MySQL**: En curso. Se ha eliminado la integración con Supabase.

## Tareas Pendientes (Prioridad Inmediata)
1.  **Migración de Base de Datos (MySQL)**:
    - [x] Configuración `.env` (MySQL local).
    - [x] Actualización de `config/database.php` (tenant driver a `mysql`).
    - [x] Actualización de `config/tenancy.php` (central connection y managers a `mysql`).
    - [ ] Ejecutar migraciones en el servidor local (XAMPP/Herd).
2.  **Configuración de Storage (Local)**:
    - [x] Desactivado Supabase S3 en `.env`.
    - [ ] Asegurar que `MEDIA_DISK=public` funcione correctamente en local.
    - [ ] Ejecutar `php artisan storage:link`.

## Notas Técnicas
- El proyecto utiliza Laravel 13 y Filament v5.6.
- Se han eliminado los scripts de migración a Supabase y archivos relacionados.
- Se utiliza `stancl/tenancy` con separación de bases de datos por prefijo `tenant_`.

---
*Este archivo sirve como punto de control para la transición a MySQL.*
