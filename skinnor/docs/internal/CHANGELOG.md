# Registro de Cambios (Changelog)

## [2026-05-25] - Corrección de Acceso Superadmin

### Cambios realizados por Gemini CLI
*   **Seguridad**: Se actualizaron las credenciales del usuario de administración global.
    *   Email anterior: `admin@skinnor.com`
    *   Email nuevo: `superadmin@skinnor.com`
    *   Password: `12345678` (Hash Bcrypt)
*   **Configuración de Panel**: 
    *   Se modificó `app/Providers/Filament/SuperadminPanelProvider.php` para habilitar el método `->login()`. Anteriormente, el panel no tenía un punto de entrada de autenticación propio.
*   **Mantenimiento**: 
    *   Limpieza de caché de rutas y configuración (`config:clear`, `route:clear`) para asegurar la disponibilidad de la nueva ruta `/superadmin/login`.

### Próximos Pasos Recomendados
1.  Implementar Multi-tenancy nativo en Filament para el panel `/admin`.
2.  Configurar redirecciones automáticas tras el login basadas en el rol del usuario.
