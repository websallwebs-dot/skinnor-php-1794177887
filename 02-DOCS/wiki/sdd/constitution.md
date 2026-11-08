# Constitución SDD — Skinnor-PHP

## Principios de diseño

1. **Landing informativa (modo Persuade):** Solo dos botones de acceso público: "Iniciar sesión" y "Crear cuenta". No revela información sensible (clínicas, dominios, directorio, IDs).
2. **Sin selector de roles en login:** El formulario de login tiene solo email y contraseña. El rol se determina por el usuario autenticado, no se selecciona en el formulario.
3. **Acceso restringido por rol desde landing:** Solo "Iniciar sesión" y "Crear cuenta" están en la landing. Admin y Superadmin acceden por URL directa, sin enlaces visibles desde la landing.
4. **Tenencia obligatoria:** Paciente, Profesional y Admin acceden mediante `/login` con middleware de tenancy (dominio de clínica). Superadmin accede por `/superadmin/login` en central (sin tenancy).
5. **Redirección por rol:** Tras autenticación, el usuario redirige a su panel correspondiente (`/paciente`, `/profesional`, `/admin`, `/superadmin`).
6. **Registro solo pacientes:** `/register` crea usuarios con rol `paciente`.

## Roles y paneles

| Rol | Panel | URL panel | Acceso desde landing | Tenancy |
|-----|-------|-----------|---------------------|---------|
| `paciente` | Paciente | `/paciente` | Sí (vía login general) | Sí |
| `profesional` | Profesional | `/profesional` | Sí (vía login general) | Sí |
| `admin` | Admin | `/admin` | No (URL directa) | Sí |
| `superadmin` | Superadmin | `/superadmin` | No (URL directa `/superadmin/login`) | No (central) |

## Credenciales de desarrollo

| Rol | Email | Contraseña |
|-----|-------|------------|
| Superadmin | `admin@skinnor.com` | `password` |
| Admin | `admin@skinnor.cl` | `password` |
| Profesional | `prof@skinnor.cl` | `password` |
| Paciente | `paciente@skinnor.cl` | `password` |
