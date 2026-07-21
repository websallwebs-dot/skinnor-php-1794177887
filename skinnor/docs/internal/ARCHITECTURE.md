# Arquitectura del Proyecto Skinnor

## Descripción General
Skinnor es una plataforma SaaS orientada a la gestión dermatológica, construida con Laravel 11 y Filament v3/v4. El sistema utiliza una arquitectura multi-panel para separar las responsabilidades de los distintos actores.

## Estructura de Paneles (Filament)
El proyecto cuenta con cuatro puntos de acceso principales:

1.  **/superadmin**: Gestionado por `SuperadminPanelProvider`. 
    *   **Acceso**: Usuarios con `role = 'superadmin'`.
    *   **Propósito**: Gestión global del sistema y administración de Clínicas.
2.  **/admin**: Gestionado por `AdminPanelProvider`.
    *   **Acceso**: Usuarios con `role = 'admin'`.
    *   **Propósito**: Administración de una clínica específica.
3.  **/profesional**: Gestionado por `ProfesionalPanelProvider`.
    *   **Acceso**: Usuarios con `role = 'profesional'`.
    *   **Propósito**: Gestión de pacientes, consultas y seguimientos por parte de los médicos.
4.  **/paciente**: Gestionado por `PacientePanelProvider`.
    *   **Acceso**: Usuarios con `role = 'paciente'`.
    *   **Propósito**: Visualización de historial, lesiones y comunicación con profesionales.

## Modelo de Datos (SaaS/Multi-tenancy)
El sistema utiliza una estrategia de "Single Database Multi-tenancy" basada en la tabla `clinicas`.

### Entidades Principales
*   **Clinica**: El Tenant principal.
*   **User**: Entidad de autenticación vinculada a una `clinica_id`. Posee un `role`.
*   **Profesional**: Perfil extendido del usuario médico.
*   **Paciente**: Perfil extendido del usuario paciente.
*   **Lesion / Seguimiento / ImagenSeguimiento**: Estructura jerárquica para el monitoreo de patologías.
*   **Consulta**: Sistema de mensajería entre pacientes y profesionales.

## Estado de la Implementación SaaS
Actualmente, el vínculo con las clínicas se realiza mediante llaves foráneas (`clinica_id`). Se recomienda la implementación del sistema nativo de Tenancy de Filament para automatizar el filtrado de datos entre clínicas.
