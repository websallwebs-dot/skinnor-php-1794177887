# Documentación del Proyecto: Skinnor

## 1. Descripción del Proyecto
Skinnor es una plataforma de gestión clínica especializada en el seguimiento dermatológico. Su misión es centralizar la gestión de pacientes, lesiones y consultas, permitiendo a los profesionales de la salud realizar un seguimiento preciso mediante imágenes y notas clínicas, facilitando la comunicación con el paciente.

## 2. Stack Tecnológico
- **Framework:** Laravel 13
- **Panel Administrativo:** Filament PHP v5.x
- **Multi-tenancy:** Stancl Tenancy v3
- **PHP:** ^8.3
- **Base de Datos:** MySQL 8.0
- **Servidor:** Local (valet/herd/xampp)

## 3. Arquitectura
La aplicación utiliza una arquitectura **Multi-database Tenancy**.
- **DB Central:** Almacena la lista de inquilinos (`tenants`), sus dominios (`domains`) y los usuarios con rol `superadmin`.
- **DB por Tenant:** Cada clínica tiene su propia base de datos física (ej. `tenant_dermatel`) que contiene todos los datos de pacientes, lesiones, consultas y usuarios específicos de ese tenant.

## 4. Base de Datos
### Central
- `tenants`: Contiene datos de configuración de la clínica (`nombre`, `plan`, `activa`, `pais`, `ciudad`).
- `domains`: Mapeo de dominios a clínicas.
- `users`: Usuarios superadministradores.

### Por Tenant
- `users`: Usuarios registrados en la clínica específica.
- `profesionales`, `pacientes`, `lesiones`, `seguimientos`, `consultas`, `imagenes_seguimiento`.

*Migraciones:* 
- Central: `php artisan migrate`
- Tenant: `php artisan tenants:migrate --tenants={id}`

## 5. Paneles Filament
- **Superadmin** (`/superadmin`): Gestión de clínicas globales.
- **Admin** (`/admin`): Gestión interna de la clínica (Usuarios, Profesionales, Pacientes).
- **Profesional** (`/profesional`): Gestión de pacientes y consultas.
- **Paciente** (`/paciente`): Consulta de lesiones y seguimientos.

*Acceso:* Definido en `User::canAccessPanel()`.

## 6. Sistema de Idiomas
- **Paquete:** `filament-language-switch`
- **Idiomas:** `es`, `en`, `pt`, `fr`.
- **Traducciones:** Ubicadas en `lang/{idioma}/`.

## 7. Levantamiento Local
1. `git clone ...`
2. `composer install`
3. Configurar `.env` con conexión MySQL (`skinnor_central`).
4. `php artisan migrate`
5. `php artisan tenants:migrate` (o crear tenant vía tinker).

## 8. Flujos Principales
- **Nueva Clínica:** Crear registro en `ClinicaResource` (Superadmin) -> Crea DB automáticamente.
- **Seguimiento:** Profesional registra lesión -> Sube imagen -> Paciente visualiza en su panel.

## 9. Problemas conocidos y Deuda Técnica
- Consistencia en versiones de Filament (transición a v5).
- Gestión de migraciones tenant en producción.

## 10. Próximos Pasos
- Completar la refactorización de recursos a v5.
- Asegurar consistencia de storage local y preparación para S3 genérico.
